<?php
/**
 * User: densh
 * Date: 08.03.2022
 * Time: 02:03
 */

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once('lib_vavt_ref_company.php');


$PAGE->set_url('/blocks/vavt_ref_company/index.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title("Витрина предприятий");
$PAGE->set_heading("Витрина предприятий");

echo $OUTPUT->header();


$data = $DB->get_records_sql("SELECT * FROM mdl_block_vavt_ref_company ORDER BY timemodified DESC");

$render = getContentHTML($data, $typenews='all');

echo $OUTPUT->render_from_template("block_vavt_ref_company/all_company", $render);



echo $OUTPUT->footer();