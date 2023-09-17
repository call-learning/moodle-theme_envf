<?php
// phpcs:ignoreFile -- ignoring core file.
/**
 * Display the calendar page.
 *
 * Restrict view to user who can view this page.
 *
 * @copyright 2003 Jon Papaioannou
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!has_capability('theme/envf:calendarview', context_system::instance())) {
    global $OUTPUT;
    // Make sure we redirect any user who has not the capability.
    $OUTPUT->notification(get_string('success'), 'notifyfailure');
    redirect(new moodle_url('/'));
}