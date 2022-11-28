<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

global $PAGE, $DB, $OUTPUT, $USER;

$PAGE->set_url('/local/vavt_scripts/chat_page.php');

$PAGE->set_title("Чаты");
$PAGE->set_heading("Чаты");

echo $OUTPUT->header();

echo $OUTPUT->render_from_template("local_vavt_scripts/chat_page", []);


echo $OUTPUT->footer();