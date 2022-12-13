<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

global $PAGE, $DB, $OUTPUT, $USER;

require_login();

$PAGE->set_url('/local/vavt_scripts/chat_page.php');

$PAGE->set_title("Чаты");
$PAGE->set_heading("Чаты");

echo $OUTPUT->header();

$itemid = get_config('local_vavt_scripts', 'content_chat_page');

$param['content'] = html_entity_decode($itemid);

echo $OUTPUT->render_from_template("local_vavt_scripts/chat_page", $param);


echo $OUTPUT->footer();