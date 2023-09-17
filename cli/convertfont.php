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
 * Add font script to the TCPDF
 *
 *
 * To point to the right folder, please add to your config.php:
 *
 *   define('PDF_CUSTOM_FONT_PATH', __DIR__ .'/theme/envf/tcpdffonts' );
 *
 * @package   theme_envf
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
global $CFG;
require_once($CFG->libdir . '/clilib.php');
require_once($CFG->libdir . '/tcpdf/tcpdf.php');

// Get the cli options.
list($options, $unrecognised) = cli_get_params([
    'help' => false,
    'fonts' => null,
    'outpath' => null
], [
    'h' => 'help',
    'i' => 'fonts',
    'o' => 'outpath'
]);

$usage = <<<EOD
convertfont - command line tool to convert fonts for the TCPDF library.

This is inpired from https://raw.githubusercontent.com/tecnickcom/TCPDF/main/tools/tcpdf_addfont.php

Usage: convertfont.php  -i fontfile

Options:
	-i
	--fonts     Comma-separated list of input font files, full path.
	-h
	--help      Display this help and exit.
EOD;

if ($unrecognised) {
    $unrecognised = implode(PHP_EOL . '  ', $unrecognised);
    cli_error(get_string('cliunknowoption', 'core_admin', $unrecognised));
}

if ($options['help'] || empty($options['fonts'])) {
    cli_writeln($usage);
    exit(2);
}

cli_writeln("\n>>> Converting fonts for TCPDF:");

cli_writeln('*** Output dir set to ' . $options['outpath']);

// Check if there are conversion errors.
$errors = false;

foreach (explode(',', $options['fonts']) as $font) {
    if (!file_exists($font)) {
        $font = $CFG->dirroot . '/' . $font;
    }
    $fontfile = realpath($font);
    $fontname = TCPDF_FONTS::addTTFfont($fontfile);
    if ($fontname === false) {
        $errors = true;
        cli_writeln("--- ERROR: can't add " . $font);
    } else {
        cli_writeln("+++ OK   : " . $fontfile . ' added as ' . $fontname);
    }
}

if ($errors) {
    cli_error("--- Process completed with ERRORS!");
    exit(4);
}

cli_writeln(">>> Process successfully completed!");
