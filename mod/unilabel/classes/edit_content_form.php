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

namespace mod_unilabel;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

/**
 * Build a moodle form and uses the form elements given by the used content type.
 * @package     mod_unilabel
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class edit_content_form extends \moodleform {
    /** @var \stdClass $_course */
    private $_course;

    /**
     * Get an options array to use files in the editor.
     *
     * @param \context $context
     * @return array
     */
    public static function editor_options($context) {
        return array(
            'maxfiles' => EDITOR_UNLIMITED_FILES,
            'noclean' => true,
            'context' => $context,
            'subdirs' => true);
    }

    /**
     * Moodle form definition method to define all needed elements.
     * It uses the elements needed by the current content type.
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;
        $this->unilabel = $this->_customdata['unilabel'];
        $this->unilabeltype = $this->_customdata['unilabeltype'];
        $this->cm = $this->_customdata['cm'];
        $this->context = \context_module::instance($this->cm->id);
        $this->_course = get_course($this->cm->course);

        $mform->addElement('hidden', 'cmid');
        $mform->setType('cmid', PARAM_INT);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('header', 'unilabelcontenthdr', get_string('editcontent', 'mod_unilabel'));

        $this->add_intro_editor();
        $this->add_plugin_form_elements();

        $this->add_action_buttons();

        $this->set_data((array) $this->unilabel);
    }

    /**
     * Does the validation of the submitted values.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $errors = $this->unilabeltype->form_validation($errors, $data, $files);
        return $errors;
    }

    /**
     * Add the intro editor as form element.
     *
     * @return void
     */
    private function add_intro_editor() {
        $mform = $this->_form;
        $mform->addElement('editor',
            'introeditor',
            get_string('unilabeltext', 'mod_unilabel'),
            array('rows' => 10),
            self::editor_options($this->context)
        );
        $mform->setType('introeditor', PARAM_RAW); // No XSS prevention here, users must be trusted.
    }

    /**
     * Set all default data while loading the form.
     *
     * @param array $defaultvalues
     * @return void
     */
    public function set_data($defaultvalues) {

        $defaultvalues['cmid'] = $this->cm->id;

        $plugindefaultvalues = $this->get_plugin_defaultvalues();

        $defaultvalues = $defaultvalues + $plugindefaultvalues;

        $draftitemid = file_get_submitted_draft_itemid('introeditor');

        $defaultvalues['introeditor']['text'] =
                                file_prepare_draft_area($draftitemid,
                                $this->context->id,
                                'mod_unilabel',
                                'intro',
                                false,
                                array('subdirs' => true),
                                $defaultvalues['intro']);
        $defaultvalues['introeditor']['format'] = $defaultvalues['introformat'];
        $defaultvalues['introeditor']['itemid'] = $draftitemid;

        parent::set_data($defaultvalues);
    }

    /**
     * Add all form elements needed by the current content type.
     *
     * @return void
     */
    private function add_plugin_form_elements() {
        $this->unilabeltype->add_form_fragment($this, $this->context);
    }

    /**
     * Get all default values from the current content type.
     *
     * @return array
     */
    private function get_plugin_defaultvalues() {
        $data = array();

        $data = $this->unilabeltype->get_form_default($data, $this->unilabel);
        return $data;
    }

    /**
     * Return the \MoodleQuickForm form so the current content type can use it.
     *
     * @return \MoodleQuickForm
     */
    public function get_mform() {
        return $this->_form;
    }

    /**
     * Return the current course.
     *
     * @return \stdClass
     */
    public function get_course() {
        return $this->_course;
    }
}
