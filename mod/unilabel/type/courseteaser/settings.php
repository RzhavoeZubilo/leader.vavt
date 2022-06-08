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
 * unilabel type course teaser
 *
 * @package     unilabeltype_courseteaser
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$page = new admin_settingpage('unilabeltype_courseteaser', get_string('pluginname', 'unilabeltype_courseteaser'));

$courseteasersettings = array();

$courseteasersettings[] = new admin_setting_configcheckbox('unilabeltype_courseteaser/active',
    get_string('active'),
    '',
    true
);

$courseteasersettings[] = new admin_setting_configcheckbox('unilabeltype_courseteaser/autorun',
    get_string('autorun', 'mod_unilabel'),
    '',
    true
);

$numbers = array_combine(range(1, 10), range(1, 10));
$courseteasersettings[] = new admin_setting_configselect('unilabeltype_courseteaser/carouselinterval',
    get_string('default_carouselinterval', 'unilabeltype_courseteaser'),
    '',
    5,
    $numbers
);

$numbers = array_combine(range(1, 6), range(1, 6));
$courseteasersettings[] = new admin_setting_configselect('unilabeltype_courseteaser/columns',
    get_string('default_columns', 'unilabeltype_courseteaser'),
    get_string('columns_help', 'unilabeltype_courseteaser'),
    4,
    $numbers
);

$select = array(
    'carousel' => get_string('carousel', 'unilabeltype_courseteaser'),
    'grid' => get_string('grid', 'unilabeltype_courseteaser'),
);
$courseteasersettings[] = new admin_setting_configselect(
    'unilabeltype_courseteaser/presentation',
    get_string('default_presentation', 'unilabeltype_courseteaser'),
    '',
    'carousel',
    $select
);

$courseteasersettings[] = new admin_setting_configcheckbox('unilabeltype_courseteaser/showintro',
    get_string('default_showintro', 'unilabeltype_courseteaser'),
    '',
    false
);

$courseteasersettings[] = new \mod_unilabel\setting_configselect_button('unilabeltype_courseteaser/custombutton',
    get_string('custombutton', 'unilabeltype_courseteaser'),
    '',
    0
);

foreach ($courseteasersettings as $setting) {
    $page->add($setting);
}

$settingscategory->add($page);

