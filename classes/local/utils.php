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
namespace theme_envf\local;
use core\session\manager;
use core_user;
use dml_exception;
use lang_string;
use moodle_exception;
use moodle_page;
use moodle_url;
/**
 * Theme constants. In one place.
 *
 * @package   theme_envf
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class utils {
    /**
     * Converts the membership config string into an array of information that can be
     * then added to the footer via the "footer_address" mustache template.
     * Structure:
     *     addresslabel|address|tel
     *
     * Example structure:
     *     Oniris;[[pix:theme_envf|logos/oniris]];https://www.oniris-nantes.fr/;Rue de la Géraudière 44322 NANTES Cedex 3
     *
     * Converted into: an object with title and absolute url
     *
     * @param moodle_page $page
     * @return array
     * @throws dml_exception
     */
    public static function convert_address_config($page) {
        $configtext = get_config('theme_envf', 'addresses');

        $lineparser = function($setting, $index, &$currentobject) use ($page) {
            if (!empty($setting)) {
                switch ($index) {
                    case 0:
                        $currentobject->fullname = trim($setting);
                        break;
                    case 1:
                        if (strpos($setting, '[[pix:') === 0) {
                            $matches = [];
                            preg_match('/\[\[pix:(.+)\|(.+)\]\]/', $setting, $matches);
                            if ($matches) {
                                $currentobject->url = $page->theme->image_url($matches[2], $matches[1]);
                            }

                        } else {
                            try {
                                $currentobject->path = (new moodle_url($setting))->out();
                            } catch (moodle_exception $e) {
                                $currentobject->path = '';
                            }
                        }
                        break;
                    case 2:
                        try {
                            $currentobject->link = (new moodle_url($setting))->out();
                        } catch (moodle_exception $e) {
                            $currentobject->link = '';
                        }
                        break;
                    case 3:
                        $currentobject->address = trim($setting);
                        break;
                }
            }
        };
        // Line separator is comma as we use '|' for the url information.
        return \theme_clboost\local\utils::convert_from_config($configtext, $lineparser, ';');
    }

    /**
     * Converts the membership config string into an array of information that can be
     * then added to the footer via the "footer_address" mustache template.
     * Structure:
     *     languagestringlabel|url
     *
     * Example structure:
     *     mentionlegales|local/mcms/index.php?p=mentions-legales
     *
     * Converted into: an object with title and absolute url
     *
     * @param moodle_page $page
     * @return array
     * @throws dml_exception
     */
    public static function convert_legallinks_config() {
        $configtext = get_config('theme_envf', 'legallinks');

        $lineparser = function($setting, $index, &$currentobject) {
            if (!empty($setting)) {
                switch ($index) {
                    case 0:
                        $currentobject->label = get_string(trim($setting), 'theme_envf');
                        break;
                    case 1:
                        try {
                            $currentobject->link = (new moodle_url($setting))->out();
                        } catch (moodle_exception $e) {
                            $currentobject->link = '';
                        }
                        break;
                }
            }
        };
        // Line separator is comma as we use '|' for the url information.
        return \theme_clboost\local\utils::convert_from_config($configtext, $lineparser, '|');
    }
}
