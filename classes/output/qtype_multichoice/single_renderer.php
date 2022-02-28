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
 * Multiple choice question renderer classes.
 *
 * @package   theme_envf
 * @copyright 2022 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_envf\output\qtype_multichoice;

use core_tag_tag;
use html_writer;
use qtype_multichoice_single_renderer;
use question_attempt;
use question_display_options;

/**
 * Subclass for generating the bits of output specific to multiple choice
 * single questions.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class single_renderer extends qtype_multichoice_single_renderer {
    /**
     * Formulation en control override.
     *
     * @param question_attempt $qa
     * @param question_display_options $options
     * @return string
     */
    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {
        $question = $qa->get_question();
        $tags = core_tag_tag::get_item_tags_array('core_question', 'question', $question->id);
        $classes = '';
        if (!empty($tags)) {
            $tags = array_map(function($tag) {
                return trim(strtolower($tag));
            }, $tags);
            $classes = join(' ', $tags);
        }
        return html_writer::div(parent::formulation_and_controls($qa, $options), $classes);
    }
}
