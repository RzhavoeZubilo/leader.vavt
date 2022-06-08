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
 * unilabel module
 *
 * @package     mod_unilabel
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the backup steps that will be used by the backup_unilabel_activity_task
 * @package     mod_unilabel
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_unilabel_activity_structure_step extends backup_activity_structure_step {

    /**
     * Define the complete unilabel structure for backup, with file and id annotations
     *
     * @return void
     */
    protected function define_structure() {
        // Define each element separated.
        $unilabel = new backup_nested_element('unilabel', array('id'), array(
            'name', 'intro', 'introformat', 'unilabeltype', 'timemodified'));

        $this->add_subplugin_structure('unilabeltype', $unilabel, true);

        // Build the tree.

        // Define sources.
        $unilabel->set_source_table('unilabel', array('id' => backup::VAR_ACTIVITYID));

        // Define id annotations.
        // Nothing to do here.

        // Define file annotations.
        $unilabel->annotate_files('mod_unilabel', 'intro', null); // This file area hasn't itemid.

        // Return the root element (unilabel), wrapped into standard activity structure.
        return $this->prepare_activity_structure($unilabel);
    }
}
