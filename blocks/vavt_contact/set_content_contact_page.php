<?php
global $CFG, $DB, $PAGE, $OUTPUT, $USER;

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir.'/filelib.php');

$PAGE->set_url('/blocks/vavt_contact/set_content_contact_page.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title("Настройка контента");
$PAGE->set_heading("Настройка контента");

require_login();

echo $OUTPUT->header();

if(!is_siteadmin()){
    echo $OUTPUT->footer();
    echo 'Только для админов';
    exit;
}

require_once('form/set_contact_content.php');

$mform = new set_contact_content();

$redirectLink = $CFG->wwwroot.'/blocks/vavt_contact/set_content_contact_page.php';
$context = context_system::instance();

if ($mform->is_cancelled()) {
    redirect(new moodle_url($redirectLink));
} else if ($data = $mform->get_data()) {

    //$content_page = html_entity_decode($data->content_page['text']);
    $content_page = htmlentities($data->content_page['text']);

    set_config('content_ccontact_page', $content_page, 'blocks_vavt_contact');

    redirect($redirectLink, '', 100);

} else {
    $toform = new stdClass();

    $itemid = get_config('blocks_vavt_contact', 'content_ccontact_page');

    $toform->content_page['text'] = html_entity_decode($itemid);

    $mform->set_data($toform);
    $mform->display();

}


echo $OUTPUT->footer();