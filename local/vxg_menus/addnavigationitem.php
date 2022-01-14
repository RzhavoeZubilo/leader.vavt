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
 * Page for adding new navigation item
 *
 * @package    local_vxg_menus
 * @copyright  Veloxnet
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

global $DB;

$menuid = optional_param('menuid', 0, PARAM_INT);

admin_externalpage_setup('local_vxg_all_menu');

if ($menuid == 0) {
    $heading = get_string('add_new', 'local_vxg_menus');
} else {
    $heading = get_string('edit', 'local_vxg_menus');
}
$PAGE->set_title($heading);
$PAGE->set_heading($heading);

$PAGE->requires->js_call_amd('local_vxg_menus/icon_picker', 'init');
$PAGE->requires->css('/local/vxg_menus/styles.css');

$selectedroles = $DB->get_records('local_vxg_menus_right', array('objecttype' => 'menu', 'objectid' => $menuid), '', 'roleid');

$selectedrolesarray = array();
foreach ($selectedroles as $selectedrole) {
    $selectedrolesarray[] = local_vxg_menus_get_role_shortname($selectedrole->roleid);
}

if ($menuid != 0) {
    $menu = $DB->get_record('local_vxg_menus', array('id' => $menuid));
}

if (isset($menu) && !empty($menu->icon)) {
    $iconarr  = explode('/', $menu->icon, 2);
    $iconname = $iconarr[1];
    $iconcomp = $iconarr[0];
} else {
    $iconname = 't/edit_menu';
    $iconcomp = 'core';
}

$mform = new \local_vxg_menus\form\add_nav_item_form(null,
    array('selectedroles' => $selectedrolesarray, 'iconname' => $iconname, 'iconcomp' => $iconcomp));

$toform['menuid'] = $menuid;
$mform->set_data($toform);

$redirecturl = new moodle_url('/local/vxg_menus/all_menu.php');
if ($mform->is_cancelled()) {

    redirect($redirecturl);
} else if ($data = $mform->get_data()) {

    if ($menuid == 0) {

        $node       = new stdClass();
        $node->name = $data->name;
        $node->lang = $data->lang;
        $node->url  = $data->url;
        if ($data->urlparam == 1) {
            $node->params = 'id';
        } else if ($data->urlparam == 2) {
            $node->params = 'course';
        } else if ($data->urlparam == 3) {
            $node->params = 'courseid';
        } else {
            $node->params = null;
        }
        $node->disabled   = $data->disabled;
        $node->icon       = $data->icon;
        $node->menu_order = $data->menu_order;

        $newid = $DB->insert_record('local_vxg_menus', $node);

        if (isset($data->roles) && !empty($data->roles)) {

            foreach ($data->roles as $shortname) {
                $roleid = local_vxg_menus_get_role_id($shortname);

                $roles             = new stdClass();
                $roles->objecttype = 'menu';
                $roles->roleid     = $roleid;
                $roles->objectid   = $newid;

                $DB->insert_record('local_vxg_menus_right', $roles);

            }
        }

    } else {

        $node       = new stdClass();
        $node->id   = $menuid;
        $node->name = $data->name;
        $node->lang = $data->lang;
        $node->url  = $data->url;
        if ($data->urlparam == 1) {
            $node->params = 'id';
        } else if ($data->urlparam == 2) {
            $node->params = 'course';
        } else if ($data->urlparam == 3) {
            $node->params = 'courseid';
        } else {
            $node->params = null;
        }
        $node->disabled   = $data->disabled;
        $node->icon       = $data->icon;
        $node->menu_order = $data->menu_order;

        $DB->update_record('local_vxg_menus', $node);
        $DB->delete_records('local_vxg_menus_right', array('objecttype' => 'menu', 'objectid' => $menuid));

        if (isset($data->roles) && !empty($data->roles)) {

            foreach ($data->roles as $shortname) {
                $roleid = local_vxg_menus_get_role_id($shortname);

                $roles             = new stdClass();
                $roles->objecttype = 'menu';
                $roles->roleid     = $roleid;
                $roles->objectid   = $node->id;

                $DB->insert_record('local_vxg_menus_right', $roles);

            }
        }
    }

    redirect($redirecturl);
}

echo $OUTPUT->header();
if ($menuid != 0) {
    if ($menu->params == 'id') {
        $menu->urlparam = 1;
    } else if ($menu->params == 'course') {
        $menu->urlparam = 2;
    } else if ($menu->params == 'courseid') {
        $menu->urlparam = 3;
    }
    $mform->set_data($menu);
}
$mform->display();
echo $OUTPUT->footer();
