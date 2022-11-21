<?php

/**
 * Данные настройки упрощают настройку меню для админа
 * для работы требуется http://moodle.org/plugins/view/local_boostnavigation
 *
 */
/* Формат, принимаемый меню
 СООБЩЕСТВО|#||||||fa-group
-Про сообщество|/blocks/vavt_contact/community.php||||||fa-group
-Новости|/blocks/vavt_event/||||||fa-newspaper-o
-Выпускники|/local/alumni/||||||fa-sitemap
-Эксперты|/local/tilda/index.php||||||fa-user
ОБУЧЕНИЕ|#||||||fa-graduation-cap
-Программа ЛидерыПРО|/course/index.php?categoryid=2||||||
 */


global $CFG, $DB, $PAGE, $OUTPUT, $USER;

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir.'/filelib.php');

$PAGE->set_url('/local/vavt_scripts/set_menu.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title("Настройка меню");
$PAGE->set_heading("Настройка меню");

echo $OUTPUT->header();


require_once('form/set_menu.php');


$mform = new set_menu();

$redirectLink = $CFG->wwwroot.'/local/vavt_scripts/set_menu.php';


if ($mform->is_cancelled()) {
    redirect(new moodle_url($redirectLink));
} else if ($data = $mform->get_data()) {

    $arr = preg_split('/\R/', $data->menulist);
    $result = '';
    foreach ($arr as $key => $value){
        if(!empty($value)){
            $arr[$key] = explode(',', $value);
            $result .= trim($arr[$key][0]).'|'.trim($arr[$key][1]).'||||||'.trim($arr[$key][2]).PHP_EOL;
        }
    }
    set_config('insertcustomnodesusers', $result, 'local_boostnavigation');
    redirect($redirectLink, '', 100);

} else {
    $toform = new stdClass();

    $text = get_config('local_boostnavigation', 'insertcustomnodesusers');

    $arr = preg_split('/\R/', $text);

    $result = '';
    foreach ($arr as $key => $value){
        if(!empty($value)) {
            $arr[$key] = explode('|', $value);
            $result .= $arr[$key][0] . ', ' . $arr[$key][1];
            if(!empty($arr[$key][7]))
                $result .= ', ' . $arr[$key][7];
            $result .= PHP_EOL;
        }
    }

    $toform->menulist = $result;

    $mform->set_data($toform);
    $mform->display();

}


echo $OUTPUT->footer();