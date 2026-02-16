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

use context_course;
use dml_exception;
use moodle_page;
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

    const TINY_HTML_BLOCKS_DEFINITION = [
        [
            'html' => '<div class="editor-styles corner-title">
  <h2>Nice title here</h2>
</div>',
            'cat' => [0],
            'name' => 'NiceTitle',
        ],
        [
            'html' => '<div class="editor-styles angled-wrap">
  <div class="angled-corners">
    <div class="angled-container">
      <h3>Text here</h3>
    </div>
  </div>
</div>',
            'cat' => [0],
            'name' => 'Corner Title',
        ],
        [
            'html' => '<div class="editor-styles wiggly-box">
  <p>Item 1</p>
  <p>Item 2</p>
</div>',
            'cat' => [0],
            'name' => 'Wiggly box',
        ],
    ];

    /**
     * Setup tiny html blocks for editor
     */
    public static function setup_tiny_html_blocks() {
        $currentconfig =get_config('tiny_htmlblock', 'items');
        $currentitems = $currentconfig ? json_decode($currentconfig, true) : [];
        $currentitems = array_column($currentitems, null, 'name');
        $newitems = array_column(self::TINY_HTML_BLOCKS_DEFINITION, null, 'name');
        //$newitems = array_merge($currentitems, $newitems);
        set_config('items', json_encode(array_values($newitems)), 'tiny_htmlblock');

    }
}
