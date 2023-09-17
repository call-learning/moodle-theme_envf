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
use theme_envf\setup;

/**
 * Custom code to be run on installing the plugin.
 * @return bool
 */
function xmldb_theme_envf_install() {
    setup::install_update();
    $postsetup = new \theme_envf\task\post_install_task();
    // We have to do this here as if not, some capabilities are not
    // yet defined when we setup the new roles at installation time.
    \core\task\manager::queue_adhoc_task($postsetup);
    return true;
}
