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
 * unilabel type accordion
 *
 * @package     unilabeltype_accordion
 * @copyright   2022 Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$page = new admin_settingpage('unilabeltype_accordion', get_string('pluginname', 'unilabeltype_accordion'));

$accordionsettings = array();

$accordionsettings[] = new admin_setting_configcheckbox('unilabeltype_accordion/active',
    get_string('active'),
    '',
    true);

$accordionsettings[] = new admin_setting_configcheckbox('unilabeltype_accordion/showintro',
    get_string('default_showintro', 'unilabeltype_accordion'),
    '',
    false
);

foreach ($accordionsettings as $setting) {
    $page->add($setting);
}

$settingscategory->add($page);
