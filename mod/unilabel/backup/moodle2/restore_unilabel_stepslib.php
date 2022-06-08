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
 * Define all the restore steps that will be used by the restore_url_activity_task
 * @package     mod_unilabel
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_unilabel_activity_structure_step extends restore_activity_structure_step {

    /**
     * Structure step to restore one unilabel activity
     */
    protected function define_structure() {

        $paths = array();
        $unilabel = new restore_path_element('unilabel', '/activity/unilabel');
        $paths[] = $unilabel;

        $this->add_subplugin_structure('unilabeltype', $unilabel);

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Create a new instance of unilabel
     *
     * @param \stdClass $data
     * @return void
     */
    protected function process_unilabel($data) {
        global $DB;

        $data = (object)$data;
        $data->course = $this->get_courseid();

        // Any changes to the list of dates that needs to be rolled should be same during course restore and course reset.
        // See MDL-9367.

        // Insert the unilabel record.
        $newitemid = $DB->insert_record('unilabel', $data);
        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    /**
     * After restoring this instance restore related files too.
     *
     * @return void
     */
    protected function after_execute() {
        // Add unilabel related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_unilabel', 'intro', null);

    }

}
