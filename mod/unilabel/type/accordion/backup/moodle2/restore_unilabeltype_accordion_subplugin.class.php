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

defined('MOODLE_INTERNAL') || die();

/**
 * Restore definition for this content type
 * @package     unilabeltype_accordion
 * @copyright   2022 Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_unilabeltype_accordion_subplugin extends restore_subplugin {

    /**
     * Returns the paths to be handled by the subplugin at unilabel level
     * @return array
     */
    protected function define_unilabel_subplugin_structure() {

        $paths = array();

        $elename = $this->get_namefor();
        $elepath = $this->get_pathfor('/unilabeltype_accordion');
        $paths[] = new restore_path_element($elename, $elepath);

        $elename = $this->get_namefor('segment');
        $elepath = $this->get_pathfor('/unilabeltype_accordion/unilabeltype_accordion_seg');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths; // And we return the interesting paths.
    }

    /**
     * Processes the element
     * @param array $data
     * @return void
     */
    public function process_unilabeltype_accordion($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->unilabelid = $this->get_new_parentid('unilabel');

        $newitemid = $DB->insert_record('unilabeltype_accordion', $data);
        $this->set_mapping($this->get_namefor(), $oldid, $newitemid, true);
    }

    /**
     * Processes the unilabeltype_accordion_segment element
     * @param array $data
     * @return void
     */
    public function process_unilabeltype_accordion_segment($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->accordionid = $this->get_new_parentid($this->get_namefor());
        $newitemid = $DB->insert_record('unilabeltype_accordion_seg', $data);
        $this->set_mapping($this->get_namefor('segment'), $oldid, $newitemid, true);

        // Restore files.
        $this->add_related_files('unilabeltype_accordion', 'heading', 'unilabeltype_accordion_segment');
        $this->add_related_files('unilabeltype_accordion', 'content', 'unilabeltype_accordion_segment');
    }

}
