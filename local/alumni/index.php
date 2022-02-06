<?php

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

//$PAGE->set_url('/local/alumni/index.php', array('id' => $course->id));
$PAGE->set_url('/local/alumni/index.php');

$PAGE->set_title("Выпускники");
$PAGE->set_heading("Выпускники");
//$PAGE->navbar->add("Выпускники");

$PAGE->requires->css(new moodle_url('/lib/tablesorter/css/theme.bootstrap_4.min.css'));
$PAGE->requires->css(new moodle_url('/lib/tablesorter/css/theme.default.min.css'));

echo $OUTPUT->header();




//$data = $DB->get_records_sql("SELECT * FROM mdl_user_info_data");
//$data = $DB->get_records_sql("SELECT * FROM mdl_user_info_field");
//print_object($data);
//$data = $DB->get_records_sql("SELECT * FROM mdl_user as u
//INNER JOIN mdl_user_info_data as uid ON uid.userid = u.id");
$arr = array();
$data = $DB->get_records_sql("SELECT * FROM mdl_user");
foreach ($data as $dat=>$d){
    $info = '';
    $obj = new stdClass();
    $info = profile_user_record($d->id);
    if($info->showindirectory == 1){
        $obj->name = $d->lastname.' '.$d->firstname;
        $obj->phone = $info->phone;
        $obj->webpage = $info->webpage;
        $obj->orgname = $info->orgname;
        $obj->region = $info->region;
        $obj->userapeciality = $info->userapeciality;
        $obj->userposition = $info->userposition;

        $arr[] = $obj;
    }

}
//print_object($data);

// получить все данные из дополнительных полей
//print_object(profile_user_record(2));

$params = ['array'=>$arr];
echo $OUTPUT->render_from_template("local_alumni/alumni", $params);



//$PAGE->requires->js('/badges/backpack.js', true);


//$PAGE->requires->js('/lib/tablesorter/js/jquery.tablesorter.js');
//$PAGE->requires->js('/lib/tablesorter/js/jquery.tablesorter.widgets.js');

$PAGE->requires->js_call_amd('local_alumni/script', 'init');

echo $OUTPUT->footer();

?>