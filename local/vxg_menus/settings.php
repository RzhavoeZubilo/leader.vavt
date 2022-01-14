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
 * Settings for the vxg_menus local plugin
 *
 * @package    local_vxg_menus
 * @copyright  Veloxnet
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once(__DIR__ . '/locallib.php');

$roles = local_vxg_menus_get_assignable_roles();

// Category.
$ADMIN->add('appearance', new admin_category('local_vxg_menus',
    get_string('side_menus', 'local_vxg_menus')));

// Category items.
$hidesettings = new admin_settingpage('local_vxg_hide_menus',
    get_string('hide_menus', 'local_vxg_menus'));

$addsettings = new admin_externalpage('local_vxg_all_menu',
    get_string('custom_menu_items', 'local_vxg_menus'), $CFG->wwwroot . '/local/vxg_menus/all_menu.php');

// Hide menus items settings.
$hidesettings->add(new admin_setting_heading('local_vxg_hide_menus', '',
    get_string('hide_side_menu_head', 'local_vxg_menus')));

// Home.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removehomenode',
    get_string('setting_removehomenode', 'local_vxg_menus'),
    '', 0));
$hidesettings->add(new admin_setting_configmulticheckbox('local_vxg_menus/homeroles',
    get_string('hide_for_roles', 'local_vxg_menus'),
    get_string('setting_removehomenode_desc', 'local_vxg_menus'), null, $roles));
// Hite to admin.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removehomenodeadmin',
    get_string('setting_hide_to_admin', 'local_vxg_menus'),
    get_string('setting_hide_to_admin_desc', 'local_vxg_menus'), 0));

// Calendar.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removecalendarnode',
    get_string('setting_removecalendarnode', 'local_vxg_menus'),
    '', 0));
$hidesettings->add(new admin_setting_configmulticheckbox('local_vxg_menus/calendarroles',
    get_string('hide_for_roles', 'local_vxg_menus'),
    get_string('setting_removecalendarnode_desc', 'local_vxg_menus'), null, $roles));
// Hite to admin.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removecalendaradmin',
    get_string('setting_hide_to_admin', 'local_vxg_menus'),
    get_string('setting_hide_to_admin_desc', 'local_vxg_menus'), 0));

// Private files.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removeprivatefilesnode',
    get_string('setting_removeprivatefilesnode', 'local_vxg_menus'),
    '', 0));

$hidesettings->add(new admin_setting_configmulticheckbox('local_vxg_menus/privatefilesroles',
    get_string('hide_for_roles', 'local_vxg_menus'),
    get_string('setting_removeprivatefilesnode_desc', 'local_vxg_menus'), null, $roles));

// Hite to admin.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removeprivatefilesnodeadmin',
    get_string('setting_hide_to_admin', 'local_vxg_menus'),
    get_string('setting_hide_to_admin_desc', 'local_vxg_menus'), 0));

$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removemycoursesnode',
    get_string('setting_removemycoursesnode', 'local_vxg_menus'),
    '', 0));

$hidesettings->add(new admin_setting_configmulticheckbox('local_vxg_menus/mycoursesroles',
    get_string('hide_for_roles', 'local_vxg_menus'),
    get_string('setting_removemycoursesnode_desc', 'local_vxg_menus'), null, $roles));

// Hite to admin.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removemycoursesnodeadmin',
    get_string('setting_hide_to_admin', 'local_vxg_menus'),
    get_string('setting_hide_to_admin_desc', 'local_vxg_menus'), 0));

// Course nodes head.
$hidesettings->add(new admin_setting_heading('local_vxg_hide_course_menus', '',
    get_string('hide_course_side_menu_head', 'local_vxg_menus')));

// Participants.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removeparticipantsnode',
    get_string('setting_removeparticipantsnode', 'local_vxg_menus'),
    '', 0));

$hidesettings->add(new admin_setting_configmulticheckbox('local_vxg_menus/participantsroles',
    get_string('hide_for_roles', 'local_vxg_menus'),
    get_string('setting_removeparticipantsnode_desc', 'local_vxg_menus'), null, $roles));

// Hite to admin.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removeparticipantsnodeadmin',
    get_string('setting_hide_to_admin', 'local_vxg_menus'),
    get_string('setting_hide_to_admin_desc', 'local_vxg_menus'), 0));

// Badges.

$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removebadgesnode',
    get_string('setting_removebadgesnode', 'local_vxg_menus'),
    '', 0));
$hidesettings->add(new admin_setting_configmulticheckbox('local_vxg_menus/badgesroles',
    get_string('hide_for_roles', 'local_vxg_menus'),
    get_string('setting_removebadgesnode_desc', 'local_vxg_menus'), null, $roles));
// Hite to admin.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removebadgesnodeadmin',
    get_string('setting_hide_to_admin', 'local_vxg_menus'),
    get_string('setting_hide_to_admin_desc', 'local_vxg_menus'), 0));

// Competencies.

$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removecompetenciesnode',
    get_string('setting_removecompetenciesnode', 'local_vxg_menus'),
    '', 0));

$hidesettings->add(new admin_setting_configmulticheckbox('local_vxg_menus/competenciesroles',
    get_string('hide_for_roles', 'local_vxg_menus'),
    get_string('setting_removecompetenciesnode_desc', 'local_vxg_menus'), null, $roles));
// Hite to admin.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removecompetenciesnodeadmin',
    get_string('setting_hide_to_admin', 'local_vxg_menus'),
    get_string('setting_hide_to_admin_desc', 'local_vxg_menus'), 0));

// Grades.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removegradesnode',
    get_string('setting_removegradesnode', 'local_vxg_menus'),
    '', 0));

$hidesettings->add(new admin_setting_configmulticheckbox('local_vxg_menus/gradesroles',
    get_string('hide_for_roles', 'local_vxg_menus'),
    get_string('setting_removegradesnode_desc', 'local_vxg_menus'), null, $roles));

// Hite to admin.
$hidesettings->add(new admin_setting_configcheckbox('local_vxg_menus/removegradesnodeadmin',
    get_string('setting_hide_to_admin', 'local_vxg_menus'),
    get_string('setting_hide_to_admin_desc', 'local_vxg_menus'), 0));

$ADMIN->add('local_vxg_menus', $hidesettings);
$ADMIN->add('local_vxg_menus', $addsettings);

$hidesettings = null;
