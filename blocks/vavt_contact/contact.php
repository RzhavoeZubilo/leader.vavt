<?php
/**
 * User: densh
 * Date: 12.03.2022
 * Time: 15:51
 */

global $DB, $PAGE, $CFG, $OUTPUT;

require_once('../../config.php');
require_once($CFG->libdir . '/filelib.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/blocks/vavt_contact/contact.php');
$PAGE->set_title("Контакты");
$PAGE->set_heading("Контакты");

echo $OUTPUT->header();
$mysetting_1 = get_config("block_vavt_contact_1");
$mysetting_2 = get_config("block_vavt_contact_2");

$arr_phone_1 = explode(',', $mysetting_1->phone);
$arr_phone_2 = explode(',',$mysetting_2->phone);

$render = array();
$render = [
    'text1'=>  $mysetting_1->text1,
    'phone1'=>  $arr_phone_1,
    'mail1'=>  $mysetting_1->mail,
    'text2'=>  $mysetting_1->text2,
    'nameadmin'=>  $mysetting_2->nameadmin,
    'phone'=>  $arr_phone_2,
    'mail'=>  $mysetting_2->mail,
    'site'=>  $mysetting_2->site,
    'address'=>  $mysetting_2->address,
    'maps'=>  $mysetting_2->maps
];
echo $OUTPUT->render_from_template("block_vavt_contact/contact", $render);

echo $OUTPUT->footer();