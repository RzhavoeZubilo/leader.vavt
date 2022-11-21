<?php

global $CFG, $DB, $PAGE, $OUTPUT, $USER;

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir.'/filelib.php');

$PAGE->set_url('/blocks/vavt_project/set_picture.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title("Настройка картинки");
$PAGE->set_heading("Настройка картинки");

echo $OUTPUT->header();


require_once('form/set_picture.php');


$mform = new set_picture();

$redirectLink = $CFG->wwwroot.'/blocks/vavt_project/set_picture.php';
$context = context_system::instance();

if ($mform->is_cancelled()) {
    redirect(new moodle_url($redirectLink));
} else if ($data = $mform->get_data()) {
    file_save_draft_area_files($data->btnadd_picture, $context->id, 'block_vavt_project', 'btnadd_picture', $data->btnadd_picture, array('subdirs' => false));
    set_config('picture_block_vavt_project', $data->btnadd_picture, 'block_vavt_project');
    set_config('link_block_vavt_project', $data->btnadd_link, 'block_vavt_project');
    redirect($redirectLink, '', 100);
} else {
    $toform = new stdClass();

    $itemid = get_config('block_vavt_project', 'picture_block_vavt_project');

    $draftitemid = file_get_submitted_draft_itemid('btnadd_picture');
    file_prepare_draft_area(
        $draftitemid,
        $context->id,
        'block_vavt_project',
        'btnadd_picture',
        $itemid,
        ['subdirs' => 0]
    );
    $toform->btnadd_picture = $draftitemid;
    $toform->btnadd_link = get_config('block_vavt_project', 'link_block_vavt_project');

    $mform->set_data($toform);
    $mform->display();

}


echo $OUTPUT->footer();