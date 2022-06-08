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
 * Unilabel type carousel
 *
 * @package     unilabeltype_carousel
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Restore definition of this content type
 * @package     unilabeltype_carousel
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_unilabeltype_carousel_subplugin extends restore_subplugin {

    /**
     * Returns the paths to be handled by the subplugin at unilabel level
     * @return array
     */
    protected function define_unilabel_subplugin_structure() {

        $paths = array();

        $elename = $this->get_namefor();
        $elepath = $this->get_pathfor('/unilabeltype_carousel');
        $paths[] = new restore_path_element($elename, $elepath);

        $elename = $this->get_namefor('slide');
        $elepath = $this->get_pathfor('/unilabeltype_carousel/unilabeltype_carousel_slide');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths; // And we return the interesting paths.
    }

    /**
     * Processes the element
     * @param array $data
     * @return void
     */
    public function process_unilabeltype_carousel($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->unilabelid = $this->get_new_parentid('unilabel');

        $newitemid = $DB->insert_record('unilabeltype_carousel', $data);
        $this->set_mapping($this->get_namefor(), $oldid, $newitemid, true);
    }

    /**
     * Processes the unilabeltype_carousel_slide element
     * @param array $data
     * @return void
     */
    public function process_unilabeltype_carousel_slide($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->carouselid = $this->get_new_parentid($this->get_namefor());
        $newitemid = $DB->insert_record('unilabeltype_carousel_slide', $data);
        $this->set_mapping($this->get_namefor('slide'), $oldid, $newitemid, true);

        // Process files.
        $this->add_related_files('unilabeltype_carousel', 'image', 'unilabeltype_carousel_slide');
        $this->add_related_files('unilabeltype_carousel', 'image_mobile', 'unilabeltype_carousel_slide');
    }

}
