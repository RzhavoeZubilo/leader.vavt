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
 * Page for deleting menus items
 *
 * @package    local_vxg_menus
 * @copyright  Veloxnet
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

global $DB;

$menuid = required_param('menuid', PARAM_INT);

admin_externalpage_setup('local_vxg_all_menu');

$heading = get_string('delete', 'local_vxg_menus');
$PAGE->set_title($heading);
$PAGE->set_heading($heading);

$mform            = new \local_vxg_menus\form\add_nav_item_form();
$toform['menuid'] = $menuid;
$mform->set_data($toform);

$redirecturl = new moodle_url('/local/vxg_menus/all_menu.php');
if ($mform->is_cancelled()) {

    redirect($redirecturl);
} else if ($data = $mform->get_data()) {

    $DB->delete_records('local_vxg_menus', array('id' => $menuid));
    $DB->delete_records('local_vxg_menus_right', array('objecttype' => 'menu', 'objectid' => $menuid));

    redirect($redirecturl);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
