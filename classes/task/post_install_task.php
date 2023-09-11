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
namespace theme_envf\task;

use core\task\adhoc_task;
use theme_envf\setup;

/**
 * Post install task
 *
 * @package     local_envf
 * @copyright   2020 Laurent David - CALL Learning <laurent@call-learning.fr>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class post_install_task extends adhoc_task {
    /**
     * Execute the installation and setup script.
     *
     * This is necessary to do it in an adhoc task as if not the capabilities are not
     * fully setup when we create the new roles. Resulting in behat testing and first install
     * failures (with some user right like the one setup after the component is fully installed)
     *
     * @return void
     */
    public function execute() {
        setup::install_update();
    }
}
