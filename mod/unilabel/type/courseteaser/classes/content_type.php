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
 * unilabel type course teaser
 *
 * @package     unilabeltype_courseteaser
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace unilabeltype_courseteaser;

/**
 * Content type definition
 * @package     unilabeltype_courseteaser
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
        $this->config = get_config('unilabeltype_courseteaser');
        if (empty($this->config->columns)) {
            $this->config->columns = 4;
        }
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
        $prefix = 'unilabeltype_courseteaser_';

        $mform->addElement('advcheckbox', $prefix.'showintro', get_string('showunilabeltext', 'unilabeltype_courseteaser'));

        $mform->addElement('header', $prefix.'hdr', $this->get_name());
        $mform->addHelpButton($prefix.'hdr', 'pluginname', 'unilabeltype_courseteaser');

        $courseoptions = array(
            'multiple' => true,
            'limittoenrolled' => !is_siteadmin(),
            'requiredcapabilities' => array(
                    'moodle/course:manageactivities',
            ),
        );
        $mform->addElement('course', $prefix.'courses', get_string('courses', 'unilabeltype_courseteaser'), $courseoptions);
        $mform->addRule($prefix.'courses', get_string('required'), 'required', null, 'client');

        $select = array(
            'carousel' => get_string('carousel', 'unilabeltype_courseteaser'),
            'grid' => get_string('grid', 'unilabeltype_courseteaser'),
        );

        $mform->addElement('select', $prefix.'presentation', get_string('presentation', 'unilabeltype_courseteaser'), $select);

        $numbers = array_combine(range(1, 6), range(1, 6));
        $mform->addElement('select', $prefix.'columns', get_string('columns', 'unilabeltype_courseteaser'), $numbers);
        $mform->disabledIf($prefix.'columns', $prefix.'presentation', 'ne', 'grid');

        // In all smaller displays we can not use 5 columns. It is not supported by bootstrap and css injection will not work here.
        $numbers = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6);
        $strdefaultcol = get_string('default_columns', 'unilabeltype_courseteaser');
        $columnsmiddle = $mform->createElement('select', $prefix.'columnsmiddle', '', $numbers);
        $defaultmiddle = $mform->createElement('advcheckbox', $prefix.'defaultmiddle', $strdefaultcol);
        $mform->addGroup(
            array(
                $columnsmiddle,
                $defaultmiddle,
            ),
            $prefix.'group_middle',
            get_string('columnsmiddle', 'unilabeltype_courseteaser'),
            array(' '),
            false
        );
        $mform->disabledIf($prefix.'columnsmiddle', $prefix.'defaultmiddle', 'checked');
        $mform->disabledIf($prefix.'group_middle', $prefix.'presentation', 'ne', 'grid');

        $columnssmall = $mform->createElement('select', $prefix.'columnssmall', '', $numbers);
        $defaultsmall = $mform->createElement('advcheckbox', $prefix.'defaultsmall', $strdefaultcol);
        $mform->addGroup(
            array(
                $columnssmall,
                $defaultsmall,
            ),
            $prefix.'group_small',
            get_string('columnssmall', 'unilabeltype_courseteaser'),
            array(' '),
            false
        );
        $mform->disabledIf($prefix.'columnssmall', $prefix.'defaultsmall', 'checked');
        $mform->disabledIf($prefix.'group_small', $prefix.'presentation', 'ne', 'grid');

        $mform->addElement(
            'checkbox',
            $prefix.'autorun',
            get_string('autorun', 'mod_unilabel'),
            ''
        );
        $autorundefault = !empty($this->config->autorun);
        $mform->setDefault($prefix.'autorun', $autorundefault);
        $mform->disabledIf($prefix.'autorun', $prefix.'presentation', 'ne', 'carousel');

        $numbers = array_combine(range(1, 10), range(1, 10));
        $mform->addElement(
            'select',
            $prefix.'carouselinterval',
            get_string('carouselinterval', 'unilabeltype_courseteaser'),
            $numbers
        );
        $mform->disabledIf($prefix.'carouselinterval', $prefix.'presentation', 'ne', 'carousel');
        $mform->hideIf($prefix.'carouselinterval', $prefix.'autorun', 'notchecked');
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
        $prefix = 'unilabeltype_courseteaser_';

        if (!$unilabeltyperecord = $this->load_unilabeltype_record($unilabel->id)) {
            $data[$prefix.'presentation'] = $this->config->presentation;
            $data[$prefix.'columns'] = $this->config->columns;
            $data[$prefix.'columnsmiddle'] = $this->get_default_col_middle($this->config->columns);
            $data[$prefix.'defaultmiddle'] = true;
            $data[$prefix.'columnssmall'] = $this->get_default_col_small();
            $data[$prefix.'defaultsmall'] = true;
            $data[$prefix.'carouselinterval'] = $this->config->carouselinterval;
            $data[$prefix.'autorun'] = $this->config->autorun;
            $data[$prefix.'showintro'] = $this->config->showintro;
        } else {
            $data[$prefix.'presentation'] = $unilabeltyperecord->presentation;
            $data[$prefix.'columns'] = $unilabeltyperecord->columns;
            if (empty($unilabeltyperecord->columnsmiddle)) {
                $data[$prefix.'columnsmiddle'] = $this->get_default_col_middle($unilabeltyperecord->columns);
                $data[$prefix.'defaultmiddle'] = true;
            } else {
                $data[$prefix.'columnsmiddle'] = $unilabeltyperecord->columnsmiddle;
                $data[$prefix.'defaultmiddle'] = false;
            }
            if (empty($unilabeltyperecord->columnssmall)) {
                $data[$prefix.'columnssmall'] = $this->get_default_col_small();
                $data[$prefix.'defaultsmall'] = true;
            } else {
                $data[$prefix.'columnssmall'] = $unilabeltyperecord->columnssmall;
                $data[$prefix.'defaultsmall'] = false;
            }

            $data[$prefix.'carouselinterval'] = $unilabeltyperecord->carouselinterval;
            $data[$prefix.'autorun'] = boolval(!empty($unilabeltyperecord->carouselinterval));
            $data[$prefix.'showintro'] = $unilabeltyperecord->showintro;
            $data[$prefix.'courses'] = explode(',', $unilabeltyperecord->courses);
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

        if (!$unilabeltyperecord = $this->load_unilabeltype_record($unilabel->id)) {
            $content = [
                'cmid' => $cm->id,
                'hasitems' => false,
            ];
            $template = 'default';
        } else {
            $intro = $this->format_intro($unilabel, $cm);
            $showintro = !empty($unilabeltyperecord->showintro);
            $items = $this->get_course_infos($unilabel);
            $content = [
                'showintro' => $showintro,
                'intro' => $showintro ? $intro : '',
                'interval' => $unilabeltyperecord->carouselinterval,
                'height' => 300,
                'items' => array_values($items),
                'hasitems' => count($items) > 0,
                'cmid' => $cm->id,
                'plugin' => 'unilabeltype_courseteaser',
            ];
            switch ($unilabeltyperecord->presentation) {
                case 'carousel':
                    $template = 'carousel';
                    if (!empty($this->config->custombutton)) {
                        $content['custombuttons'] = 1;
                        $content['fontawesomenext'] =
                            \mod_unilabel\setting_configselect_button::$buttonlist[$this->config->custombutton]['next'];
                        $content['fontawesomeprev'] =
                            \mod_unilabel\setting_configselect_button::$buttonlist[$this->config->custombutton]['prev'];

                        // To make sure we have clean html we have to put the carousel css into the <head> by using javascript.
                        $cssstring = $renderer->render_from_template('mod_unilabel/carousel_button_style', $content);
                        $content['cssjsonstring'] = json_encode($cssstring);
                    }
                    break;
                case 'grid':
                    $template = 'grid';
                    $content['colclasses'] = $this->get_bootstrap_cols(
                        $unilabeltyperecord->columns,
                        $unilabeltyperecord->columnsmiddle,
                        $unilabeltyperecord->columnssmall
                    );
                    break;
                default:
                    $template = 'default';
            }
        }
        $content = $renderer->render_from_template('unilabeltype_courseteaser/'.$template, $content);
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

        $DB->delete_records('unilabeltype_courseteaser', array('unilabelid' => $unilabelid));
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

        if (!$unilabeltyperecord = $this->load_unilabeltype_record($unilabel->id)) {
            $unilabeltyperecord = new \stdClass();
            $unilabeltyperecord->unilabelid = $unilabel->id;
        }
        $prefix = 'unilabeltype_courseteaser_';

        $unilabeltyperecord->presentation = $formdata->{$prefix.'presentation'};

        $columns = !empty($formdata->{$prefix.'columns'}) ? $formdata->{$prefix.'columns'} : 0;
        $unilabeltyperecord->columns = $columns;
        $columnsmiddle = !empty($formdata->{$prefix.'defaultmiddle'}) ? null : $formdata->{$prefix.'columnsmiddle'};
        $unilabeltyperecord->columnsmiddle = $columnsmiddle;
        $columnssmall = !empty($formdata->{$prefix.'defaultsmall'}) ? null : $formdata->{$prefix.'columnssmall'};
        $unilabeltyperecord->columnssmall = $columnssmall;

        if (!empty($formdata->{$prefix.'autorun'})) {
            $unilabeltyperecord->carouselinterval = $formdata->{$prefix.'carouselinterval'};
        } else {
            $unilabeltyperecord->carouselinterval = 0;
        }
        $unilabeltyperecord->showintro = $formdata->{$prefix.'showintro'};
        $unilabeltyperecord->courses = implode(',', $formdata->{$prefix.'courses'});

        if (empty($unilabeltyperecord->id)) {
            $unilabeltyperecord->id = $DB->insert_record('unilabeltype_courseteaser', $unilabeltyperecord);
        } else {
            $DB->update_record('unilabeltype_courseteaser', $unilabeltyperecord);
        }

        return !empty($unilabeltyperecord->id);
    }

    /**
     * Load and cache the unilabel record
     *
     * @param int $unilabelid
     * @return \stdClass
     */
    private function load_unilabeltype_record($unilabelid) {
        global $DB;

        if (empty($this->unilabeltyperecord)) {
            $this->unilabeltyperecord = $DB->get_record('unilabeltype_courseteaser', array('unilabelid' => $unilabelid));
        }
        return $this->unilabeltyperecord;
    }

    /**
     * Get all needed info to the courses
     *
     * @param \stdClass $unilabel
     * @return array
     */
    public function get_course_infos($unilabel) {
        global $DB, $CFG;

        if (class_exists('\\core_course_list_element')) {
            $useautoload = true;
            $courselistelementclass = '\\core_course_list_element';
        } else {
            $useautoload = false;
            require_once($CFG->libdir.'/coursecatlib.php');
            $courselistelementclass = '\\course_in_list';
        }

        $unilabeltyperecord = $this->load_unilabeltype_record($unilabel->id);

        if (empty($unilabeltyperecord->courses)) {
            return array();
        }

        $courseids = explode(',', $unilabeltyperecord->courses);
        $items = array();
        $counter = 0;
        foreach ($courseids as $id) {
            if (!$course = $DB->get_record('course', array('id' => $id))) {
                continue;
            }
            $cil = new $courselistelementclass($course); // Special core object with some nice methods.
            $item = new \stdClass();

            $item->courseid = $course->id;
            $item->courseurl = new \moodle_url('/course/view.php', array('id' => $course->id));
            $item->title = $course->fullname;
            if ($cil->has_course_overviewfiles()) {
                $overviewfiles = $cil->get_course_overviewfiles();

                $file = array_shift($overviewfiles);

                // We have to build our own pluginfile url so we can control the output by our self.
                $imageurl = \moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    'unilabeltype_courseteaser',
                    'overviewfiles',
                    $file->get_itemid(),
                    '/',
                    $file->get_filename()
                );
                $item->imageurl = $imageurl;
            }
            $item->nr = $counter;
            if ($counter == 0) {
                $item->first = true;
            }
            $counter++;
            $items[] = $item;
        }
        return $items;
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
