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
 * unilabel type collapsedtext
 *
 * @package     unilabeltype_collapsedtext
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Backup definition for this content type
 * @package     unilabeltype_collapsedtext
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_unilabeltype_collapsedtext_subplugin extends backup_subplugin {

    /**
     * Returns the assessment form definition to attach to 'unilabel' XML element
     * @return \backup_subplugin_element
     */
    protected function define_unilabel_subplugin_structure() {

        // XML nodes declaration.
        $subplugin = $this->get_subplugin_element();
        $subpluginwrapper = new backup_nested_element($this->get_recommended_name());
        $subplugincollapsedtext = new backup_nested_element('unilabeltype_collapsedtext', array('id'), array(
            'title', 'presentation', 'useanimation'));

        // Connect XML elements into the tree.
        $subplugin->add_child($subpluginwrapper);
        $subpluginwrapper->add_child($subplugincollapsedtext);

        // Set source to populate the data.
        $subplugincollapsedtext->set_source_table('unilabeltype_collapsedtext', array('unilabelid' => backup::VAR_ACTIVITYID));

        return $subplugin;
    }
}
