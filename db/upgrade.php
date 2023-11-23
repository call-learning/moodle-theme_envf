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
 * This file keeps track of upgrades to the theme.
 *
 *
 * @package   theme_clboost
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Main upgrade tasks to be executed on the theme version bump
 *
 * For more information, take a look to the documentation available:
 *     - Data definition API: {@link http://docs.moodle.org/dev/Data_definition_API}
 *     - Upgrade API: {@link http://docs.moodle.org/dev/Upgrade_API}
 *
 * @param int $oldversion
 * @return bool always true
 */
function xmldb_theme_envf_upgrade($oldversion) {
    global $CFG, $DB;
    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.
    // allowed version to upgrade from (v3.5.0 right now).

    if ($oldversion < 2023091700) {
        $studentcourseid  = get_config('local_envf', 'studentcourseid');
        if ($studentcourseid) {
            set_config('studentcourseid', $studentcourseid, 'theme_envf');
        }
        // Envf savepoint reached.
        upgrade_plugin_savepoint(true, 2023091700, 'theme', 'envf');
    }
    if ($oldversion < 2023112300) {
        set_config('enabledashbyrole', 1);
        set_config('forcedefaultmymoodle', 1);
        // Envf savepoint reached.
        upgrade_plugin_savepoint(true, 2023112300, 'theme', 'envf');
    }
    return true;
}
