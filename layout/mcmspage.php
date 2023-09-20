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

use local_mcms\page;

defined('MOODLE_INTERNAL') || die();

$defaultcontext = \theme_clboost\local\utils::prepare_standard_page($OUTPUT, $PAGE);

// Here is it a bit of a hack, we get the page id from the subpage id.
$page = new page($PAGE->subpage);
$pageheaderenderable = new \local_mcms\output\pageheader\pageheader($page);
$pageheaderrenderer = $PAGE->get_renderer('local_mcms', 'pageheader');

$mcmspagecontext = [
    'mcmspageheader' => $pageheaderrenderer->render($pageheaderenderable),
    'headeractions' => $PAGE->get_header_actions(),
];

echo $OUTPUT->render_from_template('theme_envf/mcmspage', array_merge($defaultcontext, $mcmspagecontext));
