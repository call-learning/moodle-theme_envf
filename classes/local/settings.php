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
 * All constant in one place
 *
 * @package   theme_envf
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_envf\local;

use admin_setting_configtext;
use admin_setting_configtextarea;
use admin_settingpage;

defined('MOODLE_INTERNAL') || die;

/**
 * Theme settings. In one place.
 *
 * @package   theme_envf
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class settings extends \theme_clboost\local\settings {

    /**
     * Additional settings
     *
     * This is intended to be overriden in the subtheme to add new pages for example.
     *
     * @param admin_settingpage $settings
     */
    protected static function additional_settings(admin_settingpage &$settings) {
        // Advanced settings.
        $page = new admin_settingpage('additionalinfo', static::get_string('additionalinfo', 'theme_envf'));

        $defaultvalue = "Ecole Nationale Vétérinaire d'Alfort;[[pix:theme_envf|logos/enva]];https://www.vet-alfort.fr/;"
                . "7, avenue du Général de Gaulle, 94700 Maisons-Alfort\n"
                . "Oniris;[[pix:theme_envf|logos/oniris]];https://www.oniris-nantes.fr/;Rue de la Géraudière 44322 NANTES Cedex 3\n"
                .
                "Ecole Nationale Vétérinaire de Toulouse;[[pix:theme_envf|logos/envt]];http://www.envt.fr/;23 Chemin des Capelles "
                . "– BP 87614 – 31 076 – Toulouse Cedex 3 – France\n"
                .
                "VetAgro Sup;[[pix:theme_envf|logos/vetagro]];http://www.vetagro-sup.fr/;1 avenue Bourgelat, 69280 Marcy-l'Etoile\n";

        $setting = new admin_setting_configtextarea('theme_envf/addresses',
                static::get_string('addresses', 'theme_envf'),
                static::get_string('addresses_desc', 'theme_envf'),
                $defaultvalue,
                PARAM_RAW);
        $page->add($setting);

        $defaultvalue = "mentionlegales|local/mcms/index.php?p=mentions-legales\n"
                . "copyright";

        $setting = new admin_setting_configtextarea('theme_envf/legallinks',
                static::get_string('legallinks', 'theme_envf'),
                static::get_string('legallinks_desc', 'theme_envf'),
                $defaultvalue,
                PARAM_RAW);
        $page->add($setting);

        $page->add(
                new admin_setting_configtext('theme_envf/nonavcoursepagetag',
                        static::get_string('nonavcoursepagetag', 'theme_envf'),
                        static::get_string('nonavcoursepagetag', 'theme_envf'),
                        'no_nav_course'
                )
        );
        $settings->add($page);
    }
}
