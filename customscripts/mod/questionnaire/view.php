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
/**
 * ENVF : Modifications
 *
 * We move directly to the relevant page instead of having intermediate pages
 *
 */
// phpcs:ignoreFile -- ignoring core file.
require_once($CFG->dirroot.'/mod/questionnaire/locallib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->dirroot.'/mod/questionnaire/questionnaire.class.php');

if (!isset($SESSION->questionnaire)) {
    $SESSION->questionnaire = new stdClass();
}
$SESSION->questionnaire->current_tab = 'view';

$id = optional_param('id', null, PARAM_INT);    // Course Module ID.
$a = optional_param('a', null, PARAM_INT);      // Or questionnaire ID.

$sid = optional_param('sid', null, PARAM_INT);  // Survey id.

list($cm, $course, $questionnaire) = questionnaire_get_standard_page_items($id, $a);
// ENVF.
if ($course->format != 'envfpsup' ) {
    return; // Back to calling function, so we display the choice activity as usual.
}
// END ENVF.
// Check login and get context.
require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);

$url = new moodle_url($CFG->wwwroot.'/mod/questionnaire/view.php');
if (isset($id)) {
    $url->param('id', $id);
} else {
    $url->param('a', $a);
}
if (isset($sid)) {
    $url->param('sid', $sid);
}

// ENVF Redirect directly to the right page instead of having an intermediate page
// This is ... only for non editor.
global $USER;
$questionnaire = new questionnaire(0, $questionnaire, $course, $cm);
$usernumresp = $questionnaire->count_submissions($USER->id);
if (!$questionnaire->capabilities->editquestions) {
    if ($questionnaire->capabilities->readownresponses && ($usernumresp > 0)) {
        $args = array('instance' => $questionnaire->id, 'user' => $USER->id);
        if ($usernumresp > 1) {
            $titletext = get_string('viewyourresponses', 'questionnaire', $usernumresp);
        } else {
            $titletext = get_string('yourresponse', 'questionnaire');
            $args['byresponse'] = 1;
            $args['action'] = 'vresp';
        }
        redirect(new moodle_url('/mod/questionnaire/myreport.php', $args));
    }
    $message = $questionnaire->user_access_messages($USER->id);
    if (!$message && $questionnaire->user_can_take($USER->id)) {
        redirect(new moodle_url('/mod/questionnaire/complete.php', array('id' => $questionnaire->cm->id)));
    }
}
// END ENVF.

// Carry on with usual.
