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
 * unilabel type collapsed text
 *
 * @package     unilabeltype_collapsedtext
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$page = new admin_settingpage('unilabeltype_collapsedtext', get_string('pluginname', 'unilabeltype_collapsedtext'));

$collapsedtextsettings = array();

$collapsedtextsettings[] = new admin_setting_configcheckbox('unilabeltype_collapsedtext/active',
    get_string('active'),
    '',
    true);

$collapsedtextsettings[] = new admin_setting_configcheckbox(
    'unilabeltype_collapsedtext/useanimation',
    get_string('useanimation', 'unilabeltype_collapsedtext'),
    '',
    1
);

$select = array(
    'collapsed' => get_string('collapsed', 'unilabeltype_collapsedtext'),
    'dialog' => get_string('dialog', 'unilabeltype_collapsedtext'),
);
$collapsedtextsettings[] = new admin_setting_configselect(
    'unilabeltype_collapsedtext/presentation',
    get_string('default_presentation', 'unilabeltype_collapsedtext'),
    '',
    'collapsed',
    $select
);

foreach ($collapsedtextsettings as $setting) {
    $page->add($setting);
}

$settingscategory->add($page);

