<?php
/**
 * User: densh
 * Date: 07.04.2022
 * Time: 00:42
 */

global $DB, $PAGE, $CFG, $OUTPUT;

require_once('../../config.php');
require_once($CFG->libdir . '/filelib.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/blocks/vavt_contact/contact.php');
$PAGE->set_title("Контакты");
$PAGE->set_heading("Контакты");

echo $OUTPUT->header();
$render = '';
echo $OUTPUT->render_from_template("block_vavt_contact/community", $render);

echo $OUTPUT->footer();