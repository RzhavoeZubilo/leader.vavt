<?php
/**
 * User: densh
 * Date: 08.03.2022
 * Time: 02:03
 */

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once('lib_vavt_news.php');


$PAGE->set_url('/blocks/vavt_news/index.php');

$PAGE->set_title("Новости");
$PAGE->set_heading("Новости");

echo $OUTPUT->header();


$data = $DB->get_records_sql("SELECT * FROM mdl_block_vavt_news ORDER BY timemodified DESC");

$render = getContentHTML($data, $typenews='all');

echo $OUTPUT->render_from_template("block_vavt_news/all_news", $render);


echo $OUTPUT->footer();