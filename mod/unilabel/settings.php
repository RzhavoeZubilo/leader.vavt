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
 * unilabel module
 *
 * @package     mod_unilabel
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$settings = null; // We add our own settings pages and do not want the standard settings link.

// The section name "modsettingunilabel" must be just like that. Otherwise we can get an section error.
$settingscategory = new \mod_unilabel\admin_settingspage_tabs('modsettingunilabel', get_string('pluginname', 'mod_unilabel'));
$settingscategory->set_description(get_string('modulename_help', 'mod_unilabel'));
$ADMIN->add('modsettings', $settingscategory);

// Go through all subplugins and add their settings pages.
$plugins = \core_component::get_plugin_list_with_file('unilabeltype', 'settings.php', false);
foreach ($plugins as $plugin => $settingspath) {
    include($settingspath);
}
