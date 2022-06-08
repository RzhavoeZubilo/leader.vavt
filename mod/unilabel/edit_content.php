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

require_once(__DIR__."/../../config.php");
require_once($CFG->dirroot.'/course/format/lib.php');

$cmid = required_param('cmid', PARAM_INT);   // The course module id.
$switchtype = optional_param('switchtype', false, PARAM_COMPONENT);

if (!$cm = get_coursemodule_from_id('unilabel', $cmid)) {
    throw new \moodle_exception('invalidcoursemodule');
}

$unilabel = $DB->get_record('unilabel', array('id' => $cm->instance), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

$section = $DB->get_record('course_sections', array('id' => $cm->section));
$courseformat = course_get_format($course->id);

$unilabeltype = \mod_unilabel\factory::get_plugin($unilabel->unilabeltype);
if (!$unilabeltype->is_active()) {
    $unilabeltype = \mod_unilabel\factory::get_plugin('simpletext');
}

if ($course->id === SITEID) {
    require_login($course, true);
} else {
    require_course_login($course, true, $cm);
}
$context = context_module::instance($cm->id);
require_capability('mod/unilabel:edit', $context);

$myurl = new \moodle_url($FULLME);
$mybaseurl = new \moodle_url($myurl);
$mybaseurl->remove_all_params();
$returnurl = $courseformat->get_view_url($section);
$strtitle = $course->shortname.': '. $unilabeltype->get_name(). ' - ' .$unilabel->name;

/** @var \moodle_page $PAGE */
$PAGE->set_url($myurl);
$PAGE->set_title($strtitle);
$PAGE->set_pagelayout('course'); // This pagelayout is also needed on behat. Without this I had an error.
$PAGE->set_heading($course->fullname);

if ($switchtype) {
    require_sesskey();
    $unilabeltype = \mod_unilabel\factory::get_plugin($switchtype);
    if (!$unilabeltype->is_active()) {
        $unilabeltype = \mod_unilabel\factory::get_plugin('simpletext');
    }

    $DB->set_field('unilabel', 'unilabeltype', $unilabeltype->get_plugintype(), array('id' => $unilabel->id));
    redirect(new \moodle_url($mybaseurl, array('cmid' => $cmid)));
}

$form = new \mod_unilabel\edit_content_form(null, array('unilabel' => $unilabel, 'cm' => $cm, 'unilabeltype' => $unilabeltype));
if ($form->is_cancelled()) {
    redirect($returnurl);
}

if ($formdata = $form->get_data()) {

    // Save the intro and after that the current unilabeltype plugin.
    if ($draftitemid = $formdata->introeditor['itemid']) {
        $formdata->intro = file_save_draft_area_files(
            $draftitemid, $context->id,
            'mod_unilabel',
            'intro',
            0,
            \mod_unilabel\edit_content_form::editor_options($context),
            $formdata->introeditor['text']
        );

        $formdata->introformat = $formdata->introeditor['format'];
    }
    $updatesuccess = true;
    if (!$DB->update_record('unilabel', $formdata)) {
        $updatesuccess = false;
    }

    if ($updatesuccess) {
        $updatesuccess = \mod_unilabel\factory::save_plugin_content($formdata, $unilabel);
    }

    if ($updatesuccess) {
        $msg = get_string('updatesuccessful', 'mod_unilabel');
        $msgtype = \core\output\notification::NOTIFY_SUCCESS;
    } else {
        $msg = get_string('updatefailed', 'mod_unilabel');
        $msgtype = \core\output\notification::NOTIFY_ERROR;
    }
    redirect($returnurl, $msg, null, $msgtype);
}

$renderer = $PAGE->get_renderer('mod_unilabel');

$plugins = \mod_unilabel\factory::get_plugin_list();
$select = new single_select(
                        new \moodle_url($mybaseurl, array('cmid' => $cmid, 'sesskey' => sesskey())),
                        'switchtype',
                        $plugins,
                        $unilabeltype->get_plugintype()
                    );
$select->label = get_string('switchtype', 'mod_unilabel');

echo $renderer->header();
echo $renderer->render($select);
$form->display();
echo $renderer->footer();
