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
 * Version details
 *
 * @package    block
 * @subpackage user_bookmarks
 * @copyright  2012 Moodle
 * @author     Authors of Admin Bookmarks:-
 *               2006 vinkmar
 *               2011 Rossiani Wijaya (updated)
 *             Authors of User Bookmarks Old Version:-
 *               2012 Gurvinder Singh (used admin bookmarks code, updated to create user bookmarks block)
 *             Authors of User Bookmarks This Version:-
 *               2013 Jonas Rueegge
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2014090501;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires  = 2013111803;        // Requires this Moodle version
$plugin->component = 'block_user_bookmarks'; // Full name of the plugin (used for diagnostics)
$plugin->release   = '1.2.7';
$plugin->cron = 300;
$plugin->maturity = MATURITY_BETA;
