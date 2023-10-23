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
namespace theme_envf;

use context_block;
use context_system;
use dml_exception;
use local_dash_by_role\context_db_by_role;
use moodle_page;
use moodle_url;


/**
 * Class setup
 *
 * Utility setup class.
 *
 * @copyright   2020 Laurent David - CALL Learning <laurent@call-learning.fr>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class setup {
    /**
     * The defaults settings
     */
    const DEFAULT_SETTINGS = [
        'moodle' => [
            'country' => 'FR',
            'timezone' => 'Europe/Paris',
            'langmenu' => false,
            'autologinguests' => true,
            'sitepolicyhandler' => 'tool_gdpr_plus',
            'custommenuitems' => "Contact|/local/mcms/index.php?p=contact",
            'customusermenuitems' => 'preferences,moodle|/user/preferences.php|t/preferences',
            'supportemail' => 'support@concours-veto-postbac.fr',
            'block_html_allowcssclasses' => true,
            'forcedefaultmymoodle' => true, // This will prevent copies from the default pages.
            'authloginviaemail' => true,
            'usetags' => true

        ],
        'local_mcms' => [
            'rootmenuitems' => "Le concours|leconcours\r\nInfos complémentaires|infocompl\r\n"
        ],
        'block_contact_form' => [
            'sendtoemail' => 'concours-veto-postbac@vet-alfort.fr',
            'sendtoname' => 'Support'
        ],
        'block_html' => [
            'allowcssclasses' => true
        ],
        'editor_atto' => [
            'toolbar' => <<<EOD
collapse = collapse
style1 = title, bold, italic
list = unorderedlist, orderedlist, indent
links = link
files = emojipicker, image, media, recordrtc, managefiles, h5p
style2 = underline, strike, subscript, superscript
style3= styles
align = align
insert = equation, charmap, table, clear
undo = undo
accessibility = accessibilitychecker, accessibilityhelper
other = html
EOD
        ],
        'tool_dataprivacy' => [
            'showdataretentionsummary' => false
        ],
        'atto_styles' => [
            'config' => <<<EOD
[
    {
        "title": "Strong Primary",
        "type": "inline",
        "classes": "atto-strong-primary",
        "preview": true
    },
    {
        "title": "CTA Normal",
        "type": "block",
        "classes": "atto-envf-cta",
        "preview": true
    },
    {
        "title": "CTA Outline",
        "type": "block",
        "classes": "atto-envf-cta-outline",
        "preview": true
    },
    {
        "title": "CTA Outline White",
        "type": "block",
        "classes": "atto-envf-cta-outline-white",
        "preview": true
    },
    {
        "title": "Big Quote",
        "type": "inline",
        "classes": "atto-envf-quote",
        "preview": false
    },
    {
        "title": "Text white block",
        "type": "block",
        "classes": "atto-text-square-white",
        "preview": true
    },
    {
        "title": "Image shadow",
        "type": "block",
        "classes": "atto_image_shadow",
        "preview": true
    },
    {
        "title": "Button",
        "type": "inline",
        "classes": "atto-envf-btn",
        "preview": true
    },
    {
        "title": "Button Secondary",
        "type": "inline",
        "classes": "atto-envf-btn-secondary",
        "preview": true
    },
    {
        "title": "Button Secondary Outline",
        "type": "inline",
        "classes": "atto-envf-btn-secondary-outline",
        "preview": true
    },
    {
        "title": "Button Outline",
        "type": "inline",
        "classes": "atto-envf-btn-outline",
        "preview": true
    }
]
EOD
        ]
    ];

    /**
     * Install updates
     */
    public static function install_update() {
        static::setup_config_values();
    }

    /**
     * Setup config values
     */
    public static function setup_config_values() {
        foreach (self::DEFAULT_SETTINGS as $pluginname => $plugindefs) {
            $plugin = $pluginname;
            if ($pluginname === 'moodle') {
                $plugin = null;
            }
            foreach ($plugindefs as $key => $value) {
                $configvalue = get_config($plugin, $key);
                if ($configvalue != $value) {
                    set_config($key, $value, $plugin);
                }
            }
        }
        filter_set_global_state("envf", TEXTFILTER_ON);
    }
}
