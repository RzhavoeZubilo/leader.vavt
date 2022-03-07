<?php
/**
 * User: densh
 * Date: 28.02.2022
 * Time: 16:01
 */

global $DB, $PAGE, $CFG, $OUTPUT;

require_once('../../config.php');
require_once('lib_faqwiki.php');

$id = optional_param('id', '0', PARAM_INT);

$PAGE->set_url('/local/faqwiki/view.php');

$PAGE->set_title("Выпускники");

$data = $DB->get_record_sql("SELECT * FROM mdl_faqwiki WHERE id = {$id}");

$PAGE->set_heading($data->name);

$PAGE->navbar->add('База знаний сообщества', new \moodle_url('/local/faqwiki/index.php'));
//$PAGE->navbar->add($namescreen, new \moodle_url('/mod/longread/view.php', array('id'=>$id, 'action'=>'editscreen', 'screenid'=>$screenid)));

echo $OUTPUT->header();


$params = (array)getParams($data->params);

$params['content'] = html_entity_decode( $params['content'], null, 'UTF-8');


$username = $DB->get_field('user', 'concat(lastname, \' \', firstname) as nameuser', ['id' => $data->usermodified]);
$userlnk = \html_writer::link(new \moodle_url('/user/profile.php', ['id' => $data->usermodified]), $username,
    array('target' => '_blank'));

$render = [
    'name' => $data->name,
    'text' => $params['content'],
    'user' => $userlnk,
    'timemodified' => date('d.m.Y', $data->timemodified)
];

echo $OUTPUT->render_from_template("local_faqwiki/item", $render);