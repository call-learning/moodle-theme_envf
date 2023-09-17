<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
namespace theme_envf;

use advanced_testcase;
use context_course;
use context_system;

/**
 * Basic Tests for LCMS pages
 *
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class access_test extends advanced_testcase {

    const CAPABILITY_CHECKS = [
        'user' => [
            'system' => [
                'theme/envf:calendarview' => false,
                'theme/envf:viewcoursebreadcrumb' => false,
            ],
            'course' => [
                'theme/envf:calendarview' => false,
                'theme/envf:viewcoursebreadcrumb' => false,
            ],
            'systemrole' => 'user',
            'courserole' => 'student',
        ],
        'editingteacher' => [
            'system' => [
                'theme/envf:calendarview' => false,
                'theme/envf:viewcoursebreadcrumb' => false,
            ],
            'course' => [
                'theme/envf:calendarview' => false,
                'theme/envf:viewcoursebreadcrumb' => true,
            ],
            'systemrole' => 'user',
            'courserole' => 'editingteacher',
        ],
        'manager' => [
            'system' => [
                'theme/envf:calendarview' => true,
                'theme/envf:viewcoursebreadcrumb' => true,
            ],
            'course' => [
                'theme/envf:calendarview' => true,
                'theme/envf:viewcoursebreadcrumb' => true,
            ],
            'systemrole' => 'manager',
            'courserole' => 'manager',
        ],
    ];

    /**
     * Get specific roles definition, check main possible security issues
     *
     * @param string $rolename
     * @param array $systemdef
     * @param array $coursedef
     * @param string $systemrole
     * @param string $courserole
     * @return void
     * @dataProvider capability_provider
     */
    public function test_setup_user_roles_basic(
        string $rolename,
        array $systemdef,
        array $coursedef,
        string $systemrole,
        string $courserole
    ) {
        $this->resetAfterTest();
        // This should be done at plugin install so we just need to check a couple of values.
        accesslib_clear_all_caches_for_unit_testing(); // Just in case something stayed in the cache.

        $allroles = get_all_roles();
        $generator = $this->getDataGenerator();
        $course = self::getDataGenerator()->create_course();
        $contexts = [];
        $contexts['course'] = context_course::instance($course->id);
        $contexts['system'] = context_system::instance();

        $rolesbyshortname = array_map(function($r) {
            return $r->shortname;
        }, $allroles);

        $rolesbyshortname = array_combine($rolesbyshortname, $allroles);
        $user = self::getDataGenerator()->create_user();
        $generator->role_assign($rolesbyshortname[$systemrole]->id, $user->id);
        $generator->enrol_user($user->id, $course->id, $courserole);
        // Check the capabilities.
        $this->setUser($user);
        foreach (['system' => $systemdef, 'course' => $coursedef] as $contextname => $capabiltiychecks) {
            foreach ($capabiltiychecks as $capname => $cancannot) {
                $this->assertEquals($cancannot, has_capability($capname, $contexts[$contextname]),
                    "User $rolename "
                    . ($cancannot ? 'should' : 'should not') . " be able to $capname in $contextname");
            }
        }
    }

    /**
     * Capability providers from Capability array.
     *
     * @return array
     */
    public function capability_provider() {
        $capabilities = [];
        foreach (self::CAPABILITY_CHECKS as $usertype => $usercontext) {
            $capabilities['Role: ' . $usertype] = [
                    'rolename' => $usertype,
                    'system' => $usercontext['system'],
                    'course' => $usercontext['course'],
                    'systemrole' => $usercontext['systemrole'],
                    'courserole' => $usercontext['courserole'],
                ];
        }
        return $capabilities;
    }

    /**
     * Get roles shortnames as an array
     *
     * @param array $rolearray
     * @return array
     */
    protected function roles_shortnames($rolearray) {
        return array_map(function($r) {
            return $r->shortname;
        }, $rolearray);
    }
}
