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
use context_course;
use context_system;
use dml_exception;
use moodle_page;
use moodle_url;
use theme_clboost\setup_utils;

/**
 * Class setup
 *
 * Utility setup class.
 *
 * @copyright   2020 Laurent David - CALL Learning <laurent@call-learning.fr>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package theme_envf
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
            'supportemail' => 'support@concours-veto-postbac.fr',
            'block_html_allowcssclasses' => true,
            'forcedefaultmymoodle' => true, // This will prevent copies from the default pages.
            'authloginviaemail' => true,
            'usetags' => true,
            'enableanalytics' => false,
        ],
        'block_html' => [
            'allowcssclasses' => true,
        ],
        'tool_dataprivacy' => [
            'showdataretentionsummary' => false,
        ],
    ];

    /**
     * Install updates
     */
    public static function install_update() {
        static::setup_config_values();
        static::setup_homepage_blocks();
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
    /**
     * Homepage block definition
     */
    const HOMEPAGE_BLOCK_DEFINITION = [
        [
            'blockname' => 'mcms',
            'showinsubcontexts' => '0',
            'defaultregion' => 'content',
            'defaultweight' => '1',
            'configdata' =>
                [
                    "title" => "Le concours post-bac des écoles nationales vétérinaires",
                    "format" => "1",
                    "classes" => "envf-homepage-block",
                    "backgroundcolor" => "",
                    "text" => '<p>Ce site n\'est pas accessible au public</p>'
                        . '<p><strong>Pour vous informer rendez-vous&nbsp;</strong>'
                        . '<a href="https://www.concours-veto-postbac.fr/">'
                        . '<span class="atto-envf-btn"><strong>ici</strong></span></a></p>',
                    "layout" => "layout_four",
                ],
            'capabilities' => [],
            'files' => [
                'side-image.png' => [
                    'filepath' => 'theme/envf/pix/side-image.jpg',
                    'filearea' => 'images',
                    'itemid' => 0,
                ],
            ],
        ],
        ['blockname' => 'html',
            'showinsubcontexts' => '0',
            'defaultregion' => 'content',
            'defaultweight' => '2',
            'configdata' => [
                "title" => "Le tableau des admissions 2025",
                "format" => "1",
                "classes" => "container",
                "text" => '<hr>
<h5>Session 2025 - 70 places par écoles</h5>                
<p>Résultats mis à jour le 10/07/2025</p>
<p>ATTENTION, <strong>lorsque les écoles sont complètes,</strong> lors de sessions précédentes,
 il n\'y a <strong>jamais eu de désistement constaté</strong> avant la fin du délai légal de la
  procédure Parcoursup, ni au moment de la rentrée universitaire.</p>
<p></p>
<table style="width: 100%; height: 189.667px;" border="1" align="center">
    <thead>
        <tr style="font-size: smaller; height: 64.3333px;">
            <th scope="col">École</th>
            <th style="text-align: center;" scope="col">Oui définitif</th>
            <th style="text-align: center;" scope="col">Oui<br>(autre vœu&nbsp;en attente)</th>
            <th style="text-align: center;" scope="col">En attente de réponse</th>
            <th style="text-align: center;" scope="col">Rang du dernier admis appelé</th>
            <th style="text-align: center;" scope="col">Rang du dernier admis 2024</th>
        </tr>
    </thead>
    <tbody>
        <tr style="height: 0px;"></tr>
        <tr style="background-color: #f8f9f9; height: 21.8px;">
            <td>ENVA</td>
            <td style="text-align: center;">70 - COMPLET</td>
            <td style="text-align: center;">0</td>
            <td style="text-align: center;">0</td>
            <td style="text-align: center;">232</td>
            <td style="text-align: center;">267</td>
        </tr>
        <tr style="background-color: #f8f9f9; height: 40.8667px;">
            <td>VetAgroSup</td>
            <td style="text-align: center;">70 - COMPLET</td>
            <td style="text-align: center;">0</td>
            <td style="text-align: center;">0</td>
            <td style="text-align: center;">231</td>
            <td style="text-align: center;">241</td>
        </tr>
        <tr style="background-color: #f8f9f9; height: 40.8667px;">
            <td>Oniris VetAgroBio</td>
            <td style="text-align: center;">70 - COMPLET</td>
            <td style="text-align: center;">0</td>
            <td style="text-align: center;">0</td>
            <td style="text-align: center;">312</td>
            <td style="text-align: center;">307</td>
        </tr>
        <tr style="background-color: #f8f9f9; height: 21.8px;">
            <td>ENVT</td>
            <td style="text-align: center;">70 - COMPLET</td>
            <td style="text-align: center;">0</td>
            <td style="text-align: center;">0</td>
            <td style="text-align: center;">290</td>
            <td style="text-align: center;">275</td>
        </tr>
    </tbody>
</table>
<hr>
<p></p>',

            ],
            'capabilities' => [],
        ],
    ];

    /**
     * Setup homepage blocks
     *
     * @throws dml_exception
     */
    public static function setup_homepage_blocks() {
        global $PAGE;
        $oldpage = $PAGE;
        $page = new moodle_page();
        $page->set_pagetype('site-index');
        $page->set_docs_path('');
        $page->set_context(context_course::instance(SITEID));
        $PAGE = $page;
        setup_utils::setup_page_blocks($page, self::HOMEPAGE_BLOCK_DEFINITION);
        $PAGE = $oldpage;
    }
}
