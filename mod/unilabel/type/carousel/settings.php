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
 * unilabel type carousel
 *
 * @package     unilabeltype_carousel
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$page = new admin_settingpage('unilabeltype_carousel', get_string('pluginname', 'unilabeltype_carousel'));

$carouselsettings = array();

$carouselsettings[] = new admin_setting_configcheckbox('unilabeltype_carousel/active',
    get_string('active'),
    '',
    true);

$carouselsettings[] = new admin_setting_configcheckbox('unilabeltype_carousel/autorun',
    get_string('autorun', 'mod_unilabel'),
    '',
    true
);

$numbers = array_combine(range(1, 10), range(1, 10));
$carouselsettings[] = new admin_setting_configselect('unilabeltype_carousel/carouselinterval',
    get_string('default_carouselinterval', 'unilabeltype_carousel'),
    '',
    5,
    $numbers
);

$numbers = array_combine(range(100, 600, 50), range(100, 600, 50));
$numbers = array(0 => get_string('autoheight', 'unilabeltype_carousel')) + $numbers;
$carouselsettings[] = new admin_setting_configselect('unilabeltype_carousel/height',
    get_string('default_height', 'unilabeltype_carousel'),
    get_string('height_help', 'unilabeltype_carousel'),
    300,
    $numbers
);

$carouselsettings[] = new admin_setting_configcheckbox('unilabeltype_carousel/showintro',
    get_string('default_showintro', 'unilabeltype_carousel'),
    '',
    false
);

$carouselsettings[] = new admin_setting_configcheckbox('unilabeltype_carousel/usemobile',
    get_string('default_usemobile', 'unilabeltype_carousel'),
    '',
    true
);

$carouselsettings[] = new \mod_unilabel\setting_configselect_button('unilabeltype_carousel/custombutton',
    get_string('custombutton', 'unilabeltype_carousel'),
    '',
    0
);

foreach ($carouselsettings as $setting) {
    $page->add($setting);
}

$settingscategory->add($page);
