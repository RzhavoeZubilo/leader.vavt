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
 * unilabel type topic teaser
 *
 * @package     unilabeltype_topicteaser
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$page = new admin_settingpage('unilabeltype_topicteaser', get_string('pluginname', 'unilabeltype_topicteaser'));

$topicteasersettings = array();

$topicteasersettings[] = new admin_setting_configcheckbox('unilabeltype_topicteaser/active',
    get_string('active'),
    '',
    true
);

$topicteasersettings[] = new admin_setting_configcheckbox('unilabeltype_topicteaser/autorun',
    get_string('autorun', 'mod_unilabel'),
    '',
    true
);

$numbers = array_combine(range(1, 10), range(1, 10));
$topicteasersettings[] = new admin_setting_configselect('unilabeltype_topicteaser/carouselinterval',
    get_string('default_carouselinterval', 'unilabeltype_topicteaser'),
    '',
    5,
    $numbers
);

$numbers = array_combine(range(1, 6), range(1, 6));
$topicteasersettings[] = new admin_setting_configselect('unilabeltype_topicteaser/columns',
    get_string('default_columns', 'unilabeltype_topicteaser'),
    get_string('columns_help', 'unilabeltype_topicteaser'),
    4,
    $numbers
);

$select = array(
    'carousel' => get_string('carousel', 'unilabeltype_topicteaser'),
    'grid' => get_string('grid', 'unilabeltype_topicteaser'),
);
$topicteasersettings[] = new admin_setting_configselect(
    'unilabeltype_topicteaser/presentation',
    get_string('default_presentation', 'unilabeltype_topicteaser'),
    '',
    'carousel',
    $select
);

$select = array(
    'opendialog' => get_string('opendialog', 'unilabeltype_topicteaser'),
    'opencourseurl' => get_string('opencourseurl', 'unilabeltype_topicteaser'),
);
$topicteasersettings[] = new admin_setting_configselect(
    'unilabeltype_topicteaser/clickaction',
    get_string('default_clickaction', 'unilabeltype_topicteaser'),
    '',
    'opendialog',
    $select
);

$topicteasersettings[] = new admin_setting_configcheckbox('unilabeltype_topicteaser/showintro',
    get_string('default_showintro', 'unilabeltype_topicteaser'),
    '',
    false
);

$topicteasersettings[] = new admin_setting_configcheckbox('unilabeltype_topicteaser/showcoursetitle',
    get_string('default_showcoursetitle', 'unilabeltype_topicteaser'),
    '',
    true
);

$topicteasersettings[] = new \mod_unilabel\setting_configselect_button('unilabeltype_topicteaser/custombutton',
    get_string('custombutton', 'unilabeltype_topicteaser'),
    '',
    0
);

foreach ($topicteasersettings as $setting) {
    $page->add($setting);
}

$settingscategory->add($page);

