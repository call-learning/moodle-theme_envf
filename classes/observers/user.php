<?php
// This file is part of Moodle - https://moodle.org/
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
namespace theme_envf\observers;

use coding_exception;
use core\event\base;
use core_user;
use dml_exception;
use moodle_exception;

/**
 * Class user observer
 *
 * @package     theme_envf
 * @copyright   2023 Laurent David - CALL Learning <laurent@call-learning.fr>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user {
    /**
     * Default role in course
     */
    const DEFAULT_COURSE_ROLE = 'student';

    /**
     * User create callback
     *
     * @param base $event
     */
    public static function user_created($event) {
        static::enrol_user_on_course($event->objectid);
    }

    /**
     * Course identifier
     *
     * @param int $userid
     */
    public static function enrol_user_on_course($userid) {
        try {
            $user = core_user::get_user($userid);
            if ($user && ($user->auth == 'psup')) {
                global $DB;
                $psupstudentcourseid = get_config('theme_envf', 'studentcourseid');
                if ($psupstudentcourseid
                        && $DB->record_exists('course', ['id' => $psupstudentcourseid])) {
                    // TODO: should we use cohort enrolment ?
                    $instance = $DB->get_record('enrol',
                            ['courseid' => $psupstudentcourseid, 'enrol' => 'manual', 'status' => ENROL_USER_ACTIVE]);
                    if ($instance) {
                        try {
                            $studentroleid = $DB->get_field('role', 'id', ['archetype' => self::DEFAULT_COURSE_ROLE]);
                        } catch (dml_exception $e) {
                            debugging('Exception detected when trying to find student role (psup) ' .
                                    $e->getMessage(), DEBUG_NORMAL, $e->getTrace());
                            throw $e;
                        }
                        $manualplugin = enrol_get_plugin('manual');
                        // We don't recover grade as it would possibly hang the login process.
                        try {
                            $manualplugin->enrol_user($instance, $user->id, $studentroleid, 0, 0, null, false);
                        } catch (coding_exception $e) {
                            debugging("Exception detected when enrolling user (psup) on course {$psupstudentcourseid} " .
                                    $e->getMessage(), DEBUG_NORMAL, $e->getTrace());
                            throw $e;
                        }
                    }
                }
            }
        } catch (moodle_exception $e) {
            debugging("Problem enrolling user {$userid} on course" .
                    $e->getMessage(), DEBUG_NORMAL, $e->getTrace());
        }
    }
}
