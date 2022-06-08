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

namespace unilabeltype_collapsedtext;

/**
 * Content type definition
 * @package     unilabeltype_collapsedtext
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class content_type extends \mod_unilabel\content_type {
    /** @var \stdClass $unilabeltyperecord */
    private $unilabeltyperecord;

    /** @var \stdClass $config */
    private $config;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        $this->config = get_config('unilabeltype_collapsedtext');
    }

    /**
     * Add elements to the activity settings form.
     *
     * @param \mod_unilabel\edit_content_form $form
     * @param \context $context
     * @return void
     */
    public function add_form_fragment(\mod_unilabel\edit_content_form $form, \context $context) {
        $mform = $form->get_mform();
        $prefix = 'unilabeltype_collapsedtext_';

        $mform->addElement('header', $prefix.'hdr', $this->get_name());
        $mform->addHelpButton($prefix.'hdr', 'pluginname', 'unilabeltype_collapsedtext');

        $mform->addElement('text', $prefix.'title', get_string('title', 'unilabeltype_collapsedtext'), array('size' => 40));
        $mform->setType($prefix.'title', PARAM_TEXT);
        $mform->addRule($prefix.'title', get_string('required'), 'required', null, 'client');

        $select = array(
            'collapsed' => get_string('collapsed', 'unilabeltype_collapsedtext'),
            'dialog' => get_string('dialog', 'unilabeltype_collapsedtext'),
        );
        $mform->addElement('select', $prefix.'presentation', get_string('presentation', 'unilabeltype_collapsedtext'), $select);

        $mform->addElement('checkbox', $prefix.'useanimation', get_string('useanimation', 'unilabeltype_collapsedtext'));

    }

    /**
     * Get the default values for the settings form
     *
     * @param array $data
     * @param \stdClass $unilabel
     * @return array
     */
    public function get_form_default($data, $unilabel) {
        global $DB;
        $prefix = 'unilabeltype_collapsedtext_';

        if (!$unilabeltyperecord = $this->load_unilabeltype_record($unilabel)) {
            $data[$prefix.'title'] = '';
            $data[$prefix.'useanimation'] = $this->config->useanimation;
            $data[$prefix.'presentation'] = $this->config->presentation;
        } else {
            $data[$prefix.'title'] = $unilabeltyperecord->title;
            $data[$prefix.'useanimation'] = $unilabeltyperecord->useanimation;
            $data[$prefix.'presentation'] = $unilabeltyperecord->presentation;
        }
        return $data;
    }

    /**
     * Get the namespace of this content type
     *
     * @return string
     */
    public function get_namespace() {
        return __NAMESPACE__;
    }

    /**
     * Get the html formated content for this type.
     *
     * @param \stdClass $unilabel
     * @param \stdClass $cm
     * @param \plugin_renderer_base $renderer
     * @return string
     */
    public function get_content($unilabel, $cm, \plugin_renderer_base $renderer) {
        $cmidfromurl = optional_param('cmid', 0, PARAM_INT);
        if (!$unilabeltyperecord = $this->load_unilabeltype_record($unilabel)) {
            $content = array();
            $template = 'default';
        } else {
            $intro = $this->format_intro($unilabel, $cm);
            $useanimation = $this->get_useanimation($unilabel);

            $content = [
                'title' => $this->get_title($unilabel),
                'content' => $intro,
                'cmid' => $cm->id,
                'useanimation' => $useanimation,
            ];

            if ($cm->id == $cmidfromurl) {
                $content['openonstart'] = true;
            }

            switch ($unilabeltyperecord->presentation) {
                case 'collapsed':
                    $template = 'collapsed';
                    break;
                case 'dialog':
                    $template = 'dialog';
                    break;
                default:
                    $template = 'default';
            }
        }

        $content = $renderer->render_from_template('unilabeltype_collapsedtext/'.$template, $content);

        return $content;
    }

    /**
     * Delete the content of this type
     *
     * @param int $unilabelid
     * @return void
     */
    public function delete_content($unilabelid) {
        global $DB;

        $DB->delete_records('unilabeltype_collapsedtext', array('unilabelid' => $unilabelid));
    }

    /**
     * Save the content from settings page
     *
     * @param \stdClass $formdata
     * @param \stdClass $unilabel
     * @return bool
     */
    public function save_content($formdata, $unilabel) {
        global $DB;
        if (!$unilabeltyperecord = $this->load_unilabeltype_record($unilabel)) {
            $unilabeltyperecord = new \stdClass();
            $unilabeltyperecord->unilabelid = $unilabel->id;
        }

        $prefix = 'unilabeltype_collapsedtext_';

        $unilabeltyperecord->title = $formdata->{$prefix.'title'};
        $unilabeltyperecord->useanimation = !empty($formdata->{$prefix.'useanimation'});
        $unilabeltyperecord->presentation = $formdata->{$prefix.'presentation'};

        if (empty($unilabeltyperecord->id)) {
            $unilabeltyperecord->id = $DB->insert_record('unilabeltype_collapsedtext', $unilabeltyperecord);
        } else {
            $DB->update_record('unilabeltype_collapsedtext', $unilabeltyperecord);
        }

        return !empty($unilabeltyperecord->id);
    }

    /**
     * Get the title which is the clickable link
     *
     * @param \stdClass $unilabel
     * @return string
     */
    public function get_title($unilabel) {
        $this->load_unilabeltype_record($unilabel);

        if (empty($this->unilabeltyperecord->title)) {
            return get_string('notitle', 'unilabeltype_collapsedtext');
        }
        return $this->unilabeltyperecord->title;
    }

    /**
     * Do we want animation or not.
     *
     * @param \stdClass $unilabel
     * @return bool
     */
    public function get_useanimation($unilabel) {
        if (empty($this->unilabeltyperecord)) {
            return $this->config->useanimation;
        }
        $this->load_unilabeltype_record($unilabel);

        return !empty($this->unilabeltyperecord->useanimation);
    }

    /**
     * Load and cache the unilabel record
     *
     * @param \stdClass $unilabel
     * @return \stdClass
     */
    private function load_unilabeltype_record($unilabel) {
        global $DB;

        if (empty($this->unilabeltyperecord)) {
            $this->unilabeltyperecord = $DB->get_record('unilabeltype_collapsedtext', array('unilabelid' => $unilabel->id));
        }
        return $this->unilabeltyperecord;
    }

    /**
     * Check that this plugin is activated on config settings.
     *
     * @return boolean
     */
    public function is_active() {
        return !empty($this->config->active);
    }
}
