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
use theme_envf\observers\user;

/**
 * Basic Tests for LCMS pages
 *
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_envf_test extends advanced_testcase {

    /**
     * Test if user enrolment works from observer
     *
     * @covers \theme_envf\observers\user
     */
    public function test_enrol_user_on_course() {
        global $CFG;
        require_once($CFG->libdir . '/enrollib.php');
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $user = $this->getDataGenerator()->create_user();
        $userpsup = $this->getDataGenerator()->create_user(['auth' => 'psup']);
        set_config('studentcourseid', $course->id, 'theme_envf');
        user::enrol_user_on_course($user->id);
        user::enrol_user_on_course($userpsup->id);
        $this->assertFalse(is_enrolled(context_course::instance($course->id), $user));
        $this->assertTrue(is_enrolled(context_course::instance($course->id), $userpsup));
    }
}


