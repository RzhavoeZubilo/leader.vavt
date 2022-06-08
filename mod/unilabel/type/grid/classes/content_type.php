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
 * unilabel type grid
 *
 * @package     unilabeltype_grid
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace unilabeltype_grid;

/**
 * Content type definition
 * @package     unilabeltype_grid
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class content_type extends \mod_unilabel\content_type {
    /** @var \stdClass $unilabeltyperecord */
    private $unilabeltyperecord;

    /** @var array $tiles */
    private $tiles;

    /** @var \stdClass $cm */
    private $cm;

    /** @var \context $context */
    private $context;

    /** @var \stdClass $config */
    private $config;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        $this->config = get_config('unilabeltype_grid');
    }

    /**
     * Add elements to the activity settings form.
     *
     * @param \mod_unilabel\edit_content_form $form
     * @param \context $context
     * @return void
     */
    public function add_form_fragment(\mod_unilabel\edit_content_form $form, \context $context) {
        $unilabeltyperecord = $this->load_unilabeltype_record($form->unilabel->id);

        $mform = $form->get_mform();
        $prefix = 'unilabeltype_grid_';

        $mform->addElement('advcheckbox', $prefix.'showintro', get_string('showunilabeltext', 'unilabeltype_grid'));

        $mform->addElement('header', $prefix.'hdr', $this->get_name());
        $mform->addHelpButton($prefix.'hdr', 'pluginname', 'unilabeltype_grid');

        $numbers = array_combine(range(1, 6), range(1, 6));
        $mform->addElement('select', $prefix.'columns', get_string('columns', 'unilabeltype_grid'), $numbers);

        // In all smaller displays we can not use 5 columns. It is not supported by bootstrap and css injection will not work here.
        $numbers = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6);
        $strdefaultcol = get_string('default_columns', 'unilabeltype_grid');
        $columnsmiddle = $mform->createElement('select', $prefix.'columnsmiddle', '', $numbers);
        $defaultmiddle = $mform->createElement('advcheckbox', $prefix.'defaultmiddle', $strdefaultcol);
        $mform->addGroup(
            array(
                $columnsmiddle,
                $defaultmiddle,
            ),
            $prefix.'group_middle',
            get_string('columnsmiddle', 'unilabeltype_grid'),
            array(' '),
            false
        );
        $mform->disabledIf($prefix.'columnsmiddle', $prefix.'defaultmiddle', 'checked');

        $columnssmall = $mform->createElement('select', $prefix.'columnssmall', '', $numbers);
        $defaultsmall = $mform->createElement('advcheckbox', $prefix.'defaultsmall', $strdefaultcol);
        $mform->addGroup(
            array(
                $columnssmall,
                $defaultsmall,
            ),
            $prefix.'group_small',
            get_string('columnssmall', 'unilabeltype_grid'),
            array(' '),
            false
        );
        $mform->disabledIf($prefix.'columnssmall', $prefix.'defaultsmall', 'checked');

        $numbers = array_combine(range(100, 600, 50), range(100, 600, 50));
        $numbers = [0 => get_string('autoheight', 'unilabeltype_grid')] + $numbers;
        $mform->addElement('select', $prefix.'height', get_string('height', 'unilabeltype_grid'), $numbers);
        $mform->addHelpButton($prefix.'height', 'height', 'unilabeltype_grid');

        $mform->addElement('advcheckbox', $prefix.'usemobile', get_string('use_mobile_images', 'unilabeltype_grid'));
        $mform->addHelpButton($prefix.'usemobile', 'use_mobile_images', 'unilabeltype_grid');

        $repeatarray = [];
        // If we want each repeated elment in a numbered group we add a header with '{no}' in its label.
        // This is replaced by the number of element.
        $repeatarray[] = $mform->createElement('header', $prefix.'tilehdr', get_string('tile', 'unilabeltype_grid').'-{no}');
        $repeatarray[] = $mform->createElement(
                                'text',
                                $prefix.'title',
                                get_string('title', 'unilabeltype_grid').'-{no}',
                                ['size' => 50]
        );
        $repeatarray[] = $mform->createElement(
                                'editor',
                                $prefix.'content',
                                get_string('content', 'unilabeltype_grid').'-{no}',
                                ['rows' => 10],
                                $this->editor_options($form->context)
        );
        $repeatarray[] = $mform->createElement(
                                'text',
                                $prefix.'url',
                                get_string('url', 'unilabeltype_grid').'-{no}',
                                ['size' => 50]
        );
        $repeatarray[] = $mform->createElement(
            'filemanager',
            $prefix.'image',
            get_string('image', 'unilabeltype_grid').'-{no}',
            null,
            [
                'maxbytes' => $form->get_course()->maxbytes,
                'maxfiles' => 1,
                'subdirs' => false,
                'accepted_types' => ['web_image'],
            ]
        );
        $repeatarray[] = $mform->createElement(
            'filemanager',
            $prefix.'image_mobile',
            get_string('image_mobile', 'unilabeltype_grid').'-{no}',
            null,
            [
                'maxbytes' => $form->get_course()->maxbytes,
                'maxfiles' => 1,
                'subdirs' => false,
                'accepted_types' => ['web_image'],
            ]
        );

        $repeatedoptions = [];
        $repeatedoptions[$prefix.'title']['type'] = PARAM_TEXT;
        $repeatedoptions[$prefix.'url']['type'] = PARAM_URL;
        $repeatedoptions[$prefix.'content']['type'] = PARAM_RAW;
        $repeatedoptions[$prefix.'image']['type'] = PARAM_FILE;
        $repeatedoptions[$prefix.'image_mobile']['type'] = PARAM_FILE;
        // Adding the help buttons.
        $repeatedoptions[$prefix.'content']['helpbutton'] = ['content', 'unilabeltype_grid'];
        $repeatedoptions[$prefix.'url']['helpbutton'] = ['url', 'unilabeltype_grid'];
        $repeatedoptions[$prefix.'image_mobile']['helpbutton'] = ['image_mobile', 'unilabeltype_grid'];

        $defaultrepeatcount = 4; // The default count for tiles.
        $repeatcount = count($this->tiles);
        if ($rest = count($this->tiles) % $defaultrepeatcount) {
            $repeatcount = count($this->tiles) + ($defaultrepeatcount - $rest);
        }
        if ($repeatcount == 0) {
            $repeatcount = $defaultrepeatcount;
        }

        $nextel = $form->repeat_elements(
            $repeatarray,
            $repeatcount,
            $repeatedoptions,
            $prefix.'chosen_tiles_count',
            $prefix.'add_more_tiles_btn',
            $defaultrepeatcount, // Each time we add 3 elements.
            get_string('addmoretiles', 'unilabeltype_grid'),
            false
        );
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

        $cm = get_coursemodule_from_instance('unilabel', $unilabel->id);
        $context = \context_module::instance($cm->id);

        $prefix = 'unilabeltype_grid_';

        // Set default data for the grid in generel.
        if (!$unilabeltyperecord = $this->load_unilabeltype_record($unilabel->id)) {
            $data[$prefix.'columns'] = $this->config->columns;
            $data[$prefix.'columnsmiddle'] = $this->get_default_col_middle($this->config->columns);
            $data[$prefix.'defaultmiddle'] = true;
            $data[$prefix.'columnssmall'] = $this->get_default_col_small();
            $data[$prefix.'defaultsmall'] = true;
            $data[$prefix.'height'] = $this->config->height;
            $data[$prefix.'showintro'] = !empty($this->config->showintro);
            $data[$prefix.'usemobile'] = !empty($this->config->usemobile);
            return $data;
        }

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

        $data[$prefix.'height'] = $unilabeltyperecord->height;
        $data[$prefix.'showintro'] = $unilabeltyperecord->showintro;
        $data[$prefix.'usemobile'] = $unilabeltyperecord->usemobile;

        // Set default data for tiles.
        if (!$tiles = $DB->get_records(
            'unilabeltype_grid_tile',
            ['gridid' => $unilabeltyperecord->id],
            'id ASC'
        )) {
            return $data;
        }

        $index = 0;
        foreach ($tiles as $tile) {
            // Prepare the title field.
            $elementname = $prefix.'title['.$index.']';
            $data[$elementname] = $tile->title;

            // Prepare the url field.
            $elementname = $prefix.'url['.$index.']';
            $data[$elementname] = $tile->url;

            // Prepare the content field.
            $elementname = $prefix.'content['.$index.']';
            $draftitemidcontent = 0;
            $data[$elementname]['text'] =
                                file_prepare_draft_area($draftitemidcontent,
                                $context->id,
                                'unilabeltype_grid',
                                'content',
                                $tile->id,
                                array('subdirs' => true),
                                $tile->content);

            $data[$elementname]['format'] = FORMAT_HTML;
            $data[$elementname]['itemid'] = $draftitemidcontent;

            // Prepare the images.
            // $draftitemid is set by the function file_prepare_draft_area().
            $draftitemidimage = 0; // This is needed to create a new draftitemid.
            file_prepare_draft_area($draftitemidimage, $context->id, 'unilabeltype_grid', 'image', $tile->id);
            $elementname = $prefix.'image['.$index.']';
            $data[$elementname] = $draftitemidimage;

            // Prepare the mobile images.
            // $draftitemid is set by the function file_prepare_draft_area().
            $draftitemidimagemobile = 0; // This is needed to create a new draftitemid.
            file_prepare_draft_area($draftitemidimagemobile, $context->id, 'unilabeltype_grid', 'image_mobile', $tile->id);
            $elementname = $prefix.'image_mobile['.$index.']';
            $data[$elementname] = $draftitemidimagemobile;
            $index++;
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
                'intro' => get_string('nocontent', 'unilabeltype_grid'),
                'cmid' => $cm->id,
                'hastiles' => false,
            ];
        } else {
            $intro = $this->format_intro($unilabel, $cm);
            $showintro = !empty($unilabeltyperecord->showintro);
            $content = [
                'showintro' => $showintro,
                'intro' => $showintro ? $intro : '',
                'columnssmall' => 1,
                'height' => $unilabeltyperecord->height,
                'autoheight' => empty($unilabeltyperecord->height),
                'tiles' => array_values($this->tiles),
                'hastiles' => count($this->tiles) > 0,
                'cmid' => $cm->id,
            ];
            $content['colclasses'] = $this->get_bootstrap_cols(
                $unilabeltyperecord->columns,
                $unilabeltyperecord->columnsmiddle,
                $unilabeltyperecord->columnssmall
            );
        }

        $content = $renderer->render_from_template('unilabeltype_grid/grid', $content);

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

        $unilabeltyperecord = $this->load_unilabeltype_record($unilabelid);

        // Delete all tiles.
        if (!empty($unilabeltyperecord)) {
            $DB->delete_records('unilabeltype_grid_tile', ['gridid' => $unilabeltyperecord->id]);
        }

        $DB->delete_records('unilabeltype_grid', ['unilabelid' => $unilabelid]);
    }

    /**
     * Save the content from settings page
     *
     * @param \stdClass $formdata
     * @param \stdClass $unilabel
     * @return bool
     */
    public function save_content($formdata, $unilabel) {
        global $DB, $USER;

        // We want to keep the tiles consistent so we start a transaction here.
        $transaction = $DB->start_delegated_transaction();

        $prefix = 'unilabeltype_grid_';

        // First save the grid record.
        if (!$unilabeltyperecord = $DB->get_record('unilabeltype_grid', ['unilabelid' => $unilabel->id])) {
            $unilabeltyperecord = new \stdClass();
            $unilabeltyperecord->unilabelid = $unilabel->id;
            $unilabeltyperecord->id = $DB->insert_record('unilabeltype_grid', $unilabeltyperecord);
        }

        $columns = !empty($formdata->{$prefix.'columns'}) ? $formdata->{$prefix.'columns'} : 0;
        $unilabeltyperecord->columns = $columns;
        $columnsmiddle = !empty($formdata->{$prefix.'defaultmiddle'}) ? null : $formdata->{$prefix.'columnsmiddle'};
        $unilabeltyperecord->columnsmiddle = $columnsmiddle;
        $columnssmall = !empty($formdata->{$prefix.'defaultsmall'}) ? null : $formdata->{$prefix.'columnssmall'};
        $unilabeltyperecord->columnssmall = $columnssmall;

        $unilabeltyperecord->height = $formdata->{$prefix.'height'};
        $unilabeltyperecord->showintro = $formdata->{$prefix.'showintro'};
        $unilabeltyperecord->usemobile = !empty($formdata->{$prefix.'usemobile'});

        $DB->update_record('unilabeltype_grid', $unilabeltyperecord);

        $fs = get_file_storage();
        $context = \context_module::instance($formdata->cmid);
        $usercontext = \context_user::instance($USER->id);

        // First: remove old tile images.
        // We use the module_context as context and this component as component.
        $fs->delete_area_files($context->id, 'unilabeltype_grid', 'image');
        $fs->delete_area_files($context->id, 'unilabeltype_grid', 'image_mobile');
        $fs->delete_area_files($context->id, 'unilabeltype_grid', 'content');

        // Second: remove old tile records.
        $DB->delete_records('unilabeltype_grid_tile', ['gridid' => $unilabeltyperecord->id]);

        // How many tiles could be defined (we have an array here)?
        // They may not all used so some could be left out.
        $potentialtilecount = $formdata->{$prefix.'chosen_tiles_count'};
        for ($i = 0; $i < $potentialtilecount; $i++) {
            // Get the draftitemids to identify the submitted files in image, imagemobile and content.
            $draftitemid = $formdata->{$prefix.'image'}[$i];
            if (!empty($unilabeltyperecord->usemobile)) {
                $draftitemidmobile = $formdata->{$prefix.'image_mobile'}[$i];
            }
            $draftitemidcontent = $formdata->{$prefix.'content'}[$i]['itemid'];

            // Do we have an image? We get this information with file_get_draft_area_info().
            $fileinfo = file_get_draft_area_info($draftitemid);
            // We only create a record if we have at least a title, a file or a content.
            $title = $formdata->{$prefix.'title'}[$i];
            $content = $formdata->{$prefix.'content'}[$i]['text'];
            if (empty($title) and $fileinfo['filecount'] < 1 and !$this->html_has_content($content)) {
                continue;
            }

            $tilerecord = new \stdClass();
            $tilerecord->gridid = $unilabeltyperecord->id;
            $tilerecord->title = $title;
            $tilerecord->url = $formdata->{$prefix.'url'}[$i];

            $tilerecord->content = ''; // Dummy content.
            $tilerecord->id = $DB->insert_record('unilabeltype_grid_tile', $tilerecord);

            // Save draft files from content and convert the pluginfile links.
            $tilerecord->content = file_save_draft_area_files($draftitemidcontent,
                        $context->id,
                        'unilabeltype_grid',
                        'content',
                        $tilerecord->id,
                        $this->editor_options($context),
                        $content);
            $DB->update_record('unilabeltype_grid_tile', $tilerecord);

            // Now we can save our draft files for image and imagemobile.
            file_save_draft_area_files($draftitemid, $context->id, 'unilabeltype_grid', 'image', $tilerecord->id);
            if (!empty($formdata->{$prefix.'usemobile'})) {
                file_save_draft_area_files(
                    $draftitemidmobile,
                            $context->id,
                            'unilabeltype_grid',
                            'image_mobile',
                            $tilerecord->id
                );
            }
        }

        $transaction->allow_commit();

        return !empty($unilabeltyperecord->id);
    }

    /**
     * Load and cache the unilabel record
     *
     * @param int $unilabelid
     * @return \stdClass
     */
    public function load_unilabeltype_record($unilabelid) {
        global $DB;

        if (empty($this->unilabeltyperecord)) {
            if (!$this->unilabeltyperecord = $DB->get_record('unilabeltype_grid', ['unilabelid' => $unilabelid])) {
                $this->tiles = [];
                return;
            }
            $this->cm = get_coursemodule_from_instance('unilabel', $unilabelid);
            $this->context = \context_module::instance($this->cm->id);

            $tiles = $DB->get_records('unilabeltype_grid_tile', ['gridid' => $this->unilabeltyperecord->id]);
            $index = 0;

            foreach ($tiles as $tile) {
                $tile->imageurl = $this->get_image_for_tile($tile);
                $tile->imagemobileurl = $this->get_image_mobile_for_tile($tile);
                $tile->title = empty($tile->title) ? get_string('tilenr', 'unilabeltype_grid', $index + 1) : $tile->title;
                $tile->content = $this->format_content($tile, $this->context);
                $tile->nr = $index;
                $index++;
            }
            $this->tiles = $tiles;
        }
        return $this->unilabeltyperecord;
    }

    /**
     * Get the image url for the given tile
     *
     * @param \stdClass $tile
     * @return string
     */
    private function get_image_for_tile($tile) {
        $fs = get_file_storage();

        $files = $fs->get_area_files($this->context->id, 'unilabeltype_grid', 'image', $tile->id, '', $includedirs = false);
        if (!$file = array_shift($files)) {
            return '';
        }
        $imageurl = \moodle_url::make_pluginfile_url(
            $this->context->id,
            'unilabeltype_grid',
            'image',
            $tile->id,
            '/',
            $file->get_filename()
        );
        return $imageurl;
    }

    /**
     * Get the mobile image url
     *
     * @param \stdClass $tile
     * @return string
     */
    private function get_image_mobile_for_tile($tile) {
        $fs = get_file_storage();

        $files = $fs->get_area_files(
            $this->context->id,
            'unilabeltype_grid',
            'image_mobile',
            $tile->id,
            '',
            $includedirs = false
        );
        if (!$file = array_shift($files)) {
            return '';
        }
        $imageurl = \moodle_url::make_pluginfile_url(
            $this->context->id,
            'unilabeltype_grid',
            'image_mobile',
            $tile->id,
            '/',
            $file->get_filename()
        );
        return $imageurl;
    }

    /**
     * Check whether ther is content or not.
     *
     * @param string $content
     * @return bool
     */
    private function html_has_content($content) {
        $searches = [
            '<br>',
            '<br />',
            '<p>',
            '</p>'
        ];

        $check = trim(str_replace($searches, '', $content));

        return !empty($check);
    }

    /**
     * Get the options array to support files in editor.
     *
     * @param \context $context
     * @return array
     */
    public function editor_options($context) {
        return [
            'maxfiles' => EDITOR_UNLIMITED_FILES,
            'noclean' => true,
            'context' => $context,
            'subdirs' => true
        ];
    }

    /**
     * Get the format options array
     *
     * @param \context $context
     * @return array
     */
    public function format_options($context) {
        return [
            'noclean' => true,
            'context' => $context
        ];
    }

    /**
     * Format the content of a tile
     *
     * @param \stdClass $tile
     * @param \context $context
     * @return string
     */
    public function format_content($tile, $context) {
        global $CFG;
        require_once($CFG->libdir.'/filelib.php');

        $options = $this->format_options($context);
        $content = file_rewrite_pluginfile_urls(
                $tile->content,
                'pluginfile.php',
                $context->id,
                'unilabeltype_grid',
                'content',
                $tile->id
        );

        return trim(format_text($content, FORMAT_HTML, $options, null));
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
