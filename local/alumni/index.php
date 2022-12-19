<?php

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

global $PAGE, $DB, $OUTPUT, $USER;

require_login();

//$PAGE->set_url('/local/alumni/index.php', array('id' => $course->id));
$PAGE->set_url('/local/alumni/index.php');

$PAGE->set_title("Выпускники");
$PAGE->set_heading("Выпускники");
//$PAGE->navbar->add("Выпускники");

$PAGE->requires->css(new moodle_url('/lib/tablesorter/css/theme.bootstrap_4.min.css'));
$PAGE->requires->css(new moodle_url('/lib/tablesorter/css/theme.default.min.css'));

echo $OUTPUT->header();

$arr = array();
$data = $DB->get_records_sql("SELECT * FROM mdl_user WHERE deleted = 0");

$text = get_config('local_alumni', 'tablefield');
$custom_field = unserialize($text);

$custom_field_header = array();
foreach ($custom_field as $key=>$value){
    if($fieldname = $DB->get_field('user_info_field', 'name', ['shortname'=>$value]))
        $obj = new stdClass();
    $obj->name = $fieldname;

    if($value == 'email') $obj->name = 'Email';
    if($value == 'city')$obj->name = 'Город';
    if($value == 'country') $obj->name = 'Страна';

    if($value == 'okved' || $value == 'category')
        $obj->type = 'class = "filter-select" data-placeholder="Выберите"';
    else $obj->type = '';

    $custom_field_header[] = $obj;

}

foreach ($data as $dat=>$d){
    $info = '';
    $obj = new stdClass();
    $info = profile_user_record($d->id);
    $userdata = $DB->get_record('user', ['id'=>$d->id]);
    $info->email = $userdata->email;
    $info->city = $userdata->city;
    if (!empty($userdata->country)) {
        $info->country = get_string($userdata->country, 'countries');
    }else $info->country = '-';

    if($info->showindirectory == 1){
        // какие контакты разрешил показывать
        $contact_show = explode(PHP_EOL, $info->contact_show);

        $obj->userid = $d->id;
        $obj->name = '<a target="_blank" href="/user/profile.php?id='.$d->id.'">'
            .$d->lastname.' '.$d->firstname.' '.$d->middlename.'</a>';

        $obj->custom_field = array();
        $str = '';

        foreach ($custom_field as $key=>$value){
//            $obj->custom_field[$value] = $info->{$value};
            if($value == 'phone'){
                if(in_array('Отображать все', $contact_show) || in_array('Телефон', $contact_show)){
                    $str .= "<td>".$info->{$value}."</td>";
                }else{
                    $str .= "<td> - </td>";
                }
            }elseif($value == 'other_contact'){
                if(in_array('Отображать все', $contact_show) || in_array('Другой контакт', $contact_show)){
                    $str .= "<td>".$info->{$value}."</td>";
                }else{
                    $str .= "<td> - </td>";
                }
            }elseif(in_array('Отображать все', $contact_show) || $value == 'email'){
                if(in_array('Отображать все', $contact_show) || in_array('Адрес электронной почты', $contact_show)){
                    $str .= "<td>".$info->{$value}."</td>";
                }else{
                    $str .= "<td> - </td>";
                }
            }else{
                $str .= "<td>".$info->{$value}."</td>";
            }

        }
        $obj->custom_field = $str;
//        $obj->phone = $info->phone;
//        $obj->webpage = $info->webpage;
//        $obj->orgname = $info->orgname;
//        $obj->region = $info->region;
//        $obj->userapeciality = $info->userapeciality;
//        $obj->okved = $info->okved;
//        $obj->userposition = $info->userposition;
//        $obj->polsa = $info->polsa;

        $obj->msg = '<a target="_blank" href="/message/index.php?id='.$d->id.'"><i class="icon fa fa-comment fa-fw " title="Сообщение" aria-label="Сообщение"></i></a>';
        if($DB->record_exists('vavt_favorite', ['usermodified' => $USER->id,  'nameplugin' => 'alumni', 'objid'=>$d->id])){
            $obj->has_addfav = 'addfav';
        }

        $arr[] = $obj;
    }

}
//print_object($data);

// получить все данные из дополнительных полей
//print_object(profile_user_record(2));

$params = ['head'=> $custom_field_header, 'array'=>$arr];
//print_object($params);
echo $OUTPUT->render_from_template("local_alumni/alumni", $params);



//$PAGE->requires->js('/badges/backpack.js', true);


//$PAGE->requires->js('/lib/tablesorter/js/jquery.tablesorter.js');
//$PAGE->requires->js('/lib/tablesorter/js/jquery.tablesorter.widgets.js');

//$PAGE->requires->js_call_amd('local_alumni/script', 'init');

echo $OUTPUT->footer();