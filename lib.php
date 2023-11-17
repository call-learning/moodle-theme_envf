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
 * Theme plugin version definition.
 *
 * @package   theme_envf
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 * @throws coding_exception
 */
function theme_envf_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    return theme_clboost\local\utils::generic_pluginfile('envf', $course, $cm, $context, $filearea, $args, $forcedownload,
        $options);
}

/**
 * Extend form password
 *
 * @param MoodleQuickForm $mform
 * @param stdClass $user
 * @return void
 * @throws coding_exception
 */
function theme_envf_extend_set_password_form(MoodleQuickForm $mform, $user) {
    $element = $mform->getElement('username2');
    $element->setAttributes(['class' => 'd-none'] + $element->getAttributes());
    $psupidelement = $mform->createElement('static', 'psupid', \auth_psup\utils::get_username_label($user->id));
    $psupidelement->setValue(auth_psup\utils::get_user_info_data($user->id, auth_psup\utils::AUTH_PSUP_USERNAME_FIELD));
    $mform->insertElementBefore($psupidelement, 'username2');
    $email = $mform->createElement('static', 'email', get_string('email'), $user->email);
    $mform->insertElementBefore($email, 'username2');
}

/**
 * Extends navigation
 *
 * @param global_navigation $nav
 * @throws coding_exception
 * @throws dml_exception
 */
function theme_envf_extend_navigation(global_navigation $nav) {
    global $PAGE;
    // Make sure we remove all the calendar references if we don't have access right.
    $context = $PAGE->context;
    if (empty($context)) {
        $context = context_system::instance();
    }
    if (!has_capability('theme/envf:calendarview', $context)) {
        $node = $nav->find('calendar', global_navigation::TYPE_CUSTOM);
        if ($node) {
            $node->remove();
        }
    }
    if ($node = $nav->find('mycourses', global_navigation::TYPE_ROOTNODE)) {
        if (!has_capability('theme/envf:viewcoursebreadcrumb', $context)) {
            $node->remove();
        }
    }
}

/**
 * Returns the main SCSS content.
 *
 * This includes the post.css file from CL Boost and only the pre.css file
 * from clboost if the one from the current theme is empty.
 * This will avoid the double inclusion of the font-awesome, moodle scss file.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 * @throws dml_exception
 */
function theme_envf_get_main_scss_content($theme) {
    global $CFG;

    $scss = file_get_contents($CFG->dirroot . '/theme/envf/scss/commons.scss');

    return  $theme->settings->scss . $scss;
}
