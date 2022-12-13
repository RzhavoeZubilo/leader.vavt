<?php
global $CFG, $DB, $PAGE, $OUTPUT, $USER;

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir.'/filelib.php');

$PAGE->set_url('/local/vavt_scripts/get_front_page.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title("Настройка контента");
$PAGE->set_heading("Настройка контента");

//require_login();

echo $OUTPUT->header();

if(!is_siteadmin()){
    echo $OUTPUT->footer();
    echo 'Только для админов';
    exit;
}



$redirectLink = $CFG->wwwroot.'/local/vavt_scripts/get_front_page.php';
$context = context_system::instance();



echo $OUTPUT->footer();