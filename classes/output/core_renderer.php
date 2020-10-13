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
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_envf\output;

use html_writer;
use theme_clboost\output\core_renderer_override_menus;
use theme_envf\local\utils;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 *
 * @package   theme_envf
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_clboost\output\core_renderer {
    use core_renderer_override_menus;

    /**
     * Return false (no compact logo)
     *
     * @param int $maxwidth The maximum width, or null when the maximum width does not matter.
     * @param int $maxheight The maximum height, or null when the maximum height does not matter.
     * @return \moodle_url|false
     */
    public function get_compact_logo_url($maxwidth = 300, $maxheight = 300) {
        return $this->get_logo_url($maxwidth, $maxheight); // No compact logo here.
    }

    /**
     * Get additional global information that can be used in this template
     *
     * @return \stdClass
     * @throws \coding_exception
     */
    public function get_template_additional_information() {
        $additionalinfo = parent::get_template_additional_information();
        $additionalinfo->orglist = utils::convert_address_config($this->page);
        $additionalinfo->legallinks = utils::convert_legallinks_config();
        $attributes = array('rel' => 'stylesheet', 'type' => 'text/css');
        $urls = $this->page->theme->css_urls($this->page);
        $code = '';
        foreach ($urls as $url) {
            $attributes['href'] = $url;
            $code .= html_writer::empty_tag('link', $attributes);
            // This id is needed in first sheet only so that theme may override YUI sheets loaded on the fly.
            unset($attributes['id']);
        }
        $additionalinfo->h5p_extra_css = $code;;
        return $additionalinfo;
    }

    // Menus.

    public function mcms_menu() {
        $renderer = $this->page->get_renderer('local_mcms', 'menu');
        return $renderer->mcms_menu();
    }

    /**
     * We want to show the custom menus as a list of links in the footer on small screens.
     * Just return the menu object exported so we can render it differently.
     */
    public function mcms_menu_menu_flat() {
        $renderer = $this->page->get_renderer('local_mcms', 'menu');
        return $renderer->mcms_menu_menu_flat();
    }

    /**
     * Add google analytics code
     */
    public function standard_head_html() {
        $output = parent::standard_head_html();

        $gacode = get_config('theme_envf', 'ganalytics');
        if ($gacode) {
            $output .= \html_writer::tag('script', '', array(
                'src' => "https://www.googletagmanager.com/gtag/js?id={$gacode}",
                'async' => ''
            ));
            $output .= \html_writer::script("
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                        gtag('js', new Date());
                        gtag('config', '{$gacode}');
                    "
            );
        }
        return $output;
    }
}