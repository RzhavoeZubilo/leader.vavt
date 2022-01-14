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
 * Adding new organisation form.
 *
 * @package    local_vxg_menus
 * @copyright  Veloxnet
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_vxg_menus\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Adding new organisation form.
 *
 *
 * @package    local_vxg_menus
 * @copyright  Veloxnet
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class add_nav_item_form extends \moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        global $OUTPUT;

        $mform = $this->_form;

        $languages = array('' => \get_string('all'));
        $languages += get_string_manager()->get_list_of_translations();

        $roles          = local_vxg_menus_get_assignable_roles();
        $size           = count($roles);
        $iconname = $this->_customdata['iconname'];
        $iconcomp = $this->_customdata['iconcomp'];
        $selectedroles = $this->_customdata['selectedroles'];

        $styles = array('style' => 'width:50%;');
        $mform->addElement('text', 'name', \get_string('name', 'local_vxg_menus'), $styles);
        $mform->addHelpButton('name', 'name', 'local_vxg_menus');
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('select', 'lang', \get_string('lang', 'local_vxg_menus'), $languages, $styles);
        $mform->setType('lang', PARAM_LANG);
        $mform->addHelpButton('lang', 'lang', 'local_vxg_menus');

        $mform->addElement('text', 'url', 'url', $styles);
        $mform->addHelpButton('url', 'url', 'local_vxg_menus');
        $mform->addRule('url', null, 'required', null, 'client');
        $mform->setType('url', PARAM_LOCALURL);

        $radioarray = array();
        $radioarray[] = $mform->createElement('radio', 'urlparam', '', get_string('noparam', 'local_vxg_menus'), 0, array());
        $radioarray[] = $mform->createElement('radio', 'urlparam', '', 'id', 1, array());
        $radioarray[] = $mform->createElement('radio', 'urlparam', '', 'course', 2, array());
        $radioarray[] = $mform->createElement('radio', 'urlparam', '', 'courseid', 3, array());
        $mform->addGroup($radioarray, 'radioar', get_string('urlparam', 'local_vxg_menus'), array('<br>'), false);
        $mform->addHelpButton('radioar', 'urlparam', 'local_vxg_menus');
        $mform->setDefault('urlparam', 0);

        $icongroup = array();
        $icongroup[] =& $mform->createElement('html',
        $OUTPUT->pix_icon($iconname, 'icon', $iconcomp, array('class' => 'selected_icon')));

        $icongroup[] =& $mform->createElement('html',
        '<button type="button" class="btn btn-primary" data-key="icon_picker">'.
        get_string('select-icon', 'local_vxg_menus') .'</button>');

        $mform->addGroup($icongroup, 'icongroup', get_string('icon', 'local_vxg_menus'), ' ', false);

        $mform->addElement('hidden', 'icon', $iconcomp. '/' . $iconname);
        $mform->setType('icon', PARAM_RAW);

        $mform->addElement('advcheckbox', 'disabled', get_string('disabled', 'local_vxg_menus'));
        $mform->addHelpButton('disabled', 'disabled', 'local_vxg_menus');
        $mform->setType('disabled', PARAM_INT);

        $orders = ['1' => get_string('front', 'local_vxg_menus'),
                   '2'  => get_string('back', 'local_vxg_menus')];
        $mform->addElement('select', 'menu_order', get_string('order', 'local_vxg_menus'), $orders);
        $mform->setType('disabled', PARAM_INT);

        $select = $mform->addElement('select', 'roles',
            get_string('roles', 'local_vxg_menus'), $roles);
        $select->setMultiple(true);
        $select->setSize($size);
        $select->setSelected($selectedroles);

        $mform->addElement('hidden', 'menuid', 0);
        $mform->setType('menuid', PARAM_INT);

        $this->add_action_buttons();

    }

}
