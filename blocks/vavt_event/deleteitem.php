<?php
/**
 * User: densh
 * Date: 28.02.2022
 * Time: 18:29
 */

global $CFG, $DB, $PAGE, $OUTPUT, $USER;

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$PAGE->set_url('/local/faqwiki/adding.php');

$PAGE->set_title("База знаний");
$PAGE->set_heading("База знаний сообщества");

echo $OUTPUT->header();

$id = optional_param('id', '0', PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);
$contextid = optional_param('contextid', '0', PARAM_INT);

if($id <> 0 && $action == 'deleteitem'){

    $fs = get_file_storage();

    $fs->delete_area_files($contextid, 'block_vavt_event', 'pictures', $id);

    $DB->delete_records('block_vavt_event', array('id' => $id));
    $text = 'Элемент удален';
    $alertClass = 'm-t-2 alert alert-info';

//$redirectLink = new \moodle_url($PAGE->url, ['action' => 'editscreen', 'screenid' => $screenID]);

    $redirectLink = new \moodle_url('/blocks/vavt_event/index.php');
    $html = \html_writer::div($text, $alertClass);

    redirect($redirectLink, '', 1500);

    echo $html;
}



echo $OUTPUT->footer();