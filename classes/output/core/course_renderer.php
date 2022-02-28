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
 * Presets management
 *
 * @package   theme_clboost
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_envf\output\core;

use core_course\output\activity_navigation;
use core_course_renderer;
use core_tag_tag;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 *
 * @package   theme_envf
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_renderer extends core_course_renderer {
    /**
     * Renders the activity navigation.
     *
     * Defer to template.
     *
     * @param activity_navigation $page
     * @return string html for the page
     */
    public function render_activity_navigation(activity_navigation $page) {
        $courseid = 0;
        if ($this->page->context->contextlevel == CONTEXT_COURSE) {
            $courseid = $this->page->course->id;
        }
        if ($this->page->context->contextlevel == CONTEXT_MODULE) {
            $courseid = $this->page->cm->course;
        }
        if ($courseid) {
            $nocoursenavtag = get_config('theme_envf', 'nonavcoursepagetag');
            $tags = core_tag_tag::get_item_tags('core', 'course', $courseid);
            if (!empty($tags)) {
                foreach ($tags as $t) {
                    if ($t->rawname == $nocoursenavtag) {
                        return ""; // No nav here.
                    }
                }
            }
        }
        $data = $page->export_for_template($this->output);
        $data->addditionalclasses = 'mt-5'; // Margin top.
        return $this->output->render_from_template('core_course/activity_navigation', $data);
    }
}
