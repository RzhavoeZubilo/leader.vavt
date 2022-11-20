<?php

global $CFG, $DB, $PAGE, $OUTPUT, $USER;

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir.'/filelib.php');

$PAGE->set_url('/blocks/vavt_fav_alumni/set_field_view.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title("Настройка меню");
$PAGE->set_heading("Настройка меню");

echo $OUTPUT->header();


require_once('form/set_field_view.php');


$mform = new set_field_view();

$redirectLink = $CFG->wwwroot.'/blocks/vavt_fav_alumni/set_field_view.php';


if ($mform->is_cancelled()) {
    redirect(new moodle_url($redirectLink));
} else if ($data = $mform->get_data()) {

//    $arr = preg_split('/\R/', $data->menulist);
//    $result = '';
//    foreach ($arr as $key => $value){
//        if(!empty($value)){
//            $arr[$key] = explode(',', $value);
//            $result .= trim($arr[$key][0]).'|'.trim($arr[$key][1]).'||||||'.trim($arr[$key][2]).PHP_EOL;
//        }
//    }

    $result = serialize($data->alumnifield);
    set_config('tablefield', $result, 'local_alumni');
    redirect($redirectLink, '', 100);

} else {
    $toform = new stdClass();

    $text = get_config('local_alumni', 'tablefield');
    $result = unserialize($text);
    $toform->alumnifield = $result;

    $mform->set_data($toform);
    $mform->display();

}


echo $OUTPUT->footer();