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
namespace theme_envf\output;

use cache;
use context_system;
use moodle_url;
use tool_policy\api;
use tool_policy\output\page_agreedocs;

/**
 * Represents a page for showing all the policy documents which a user has to agree to.
 *
 * @package   theme_envf
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class page_agreedocs_envf extends page_agreedocs {
    /**
     * Sets up the global $PAGE and performs the access checks.
     *
     * @param int $userid
     */
    protected function prepare_global_page_access($userid) {
        global $PAGE, $SITE, $USER;

        // Guest users or not logged users (but the users during the signup process) are not allowed to access to this page.
        $newsignupuser = cache::make('core', 'presignup')->get('tool_policy_issignup');
        if (!$this->isexistinguser && !$newsignupuser) {
            $this->redirect_to_previous_url();
        }

        // Check for correct user capabilities.
        if ($this->isexistinguser) {
            // For existing users, it's needed to check if they have the capability for accepting policies.
            api::can_accept_policies($this->listdocs, $this->behalfid, true);
        } else {
            // For new users, the behalfid parameter is ignored.
            if ($this->behalfid) {
                redirect(new moodle_url('/admin/tool/policy/index.php'));
            }
        }

        // If the current user has the $USER->policyagreed = 1 or $userpolicyagreed = 1
        // redirect to the return page.
        $hasagreedsignupuser = !$this->isexistinguser && $this->signupuserpolicyagreed;
        $hasagreedloggeduser = $USER->id == $userid && !empty($USER->policyagreed);
        if (!is_siteadmin() && ($hasagreedsignupuser || $hasagreedloggeduser)) {
            $this->redirect_to_previous_url();
        }

        $myparams = [];
        if ($this->isexistinguser && !empty($this->behalfid) && $this->behalfid != $USER->id) {
            $myparams['userid'] = $this->behalfid;
        }
        $myurl = new moodle_url('/admin/tool/policy/index.php', $myparams);

        // Redirect to policy docs before the consent page.
        $this->redirect_to_policies($userid, $myurl); // TODO: Check that this is still Good for ENVF.

        // Page setup.
        $PAGE->set_context(context_system::instance());
        $PAGE->set_url($myurl);
        $PAGE->set_heading($SITE->fullname);
        $PAGE->set_title(get_string('policiesagreements', 'tool_policy'));
        $PAGE->navbar->add(get_string('policiesagreements', 'tool_policy'), new moodle_url('/admin/tool/policy/index.php'));
    }
}
