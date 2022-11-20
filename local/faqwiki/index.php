<?php

global $DB, $PAGE, $CFG, $OUTPUT;

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once('lib_faqwiki.php');

define('MODULE_URL', '/local/faqwiki/index.php');

//$PAGE->set_url('/local/alumni/index.php', array('id' => $course->id));
$PAGE->set_url('/local/faqwiki/index.php');

$PAGE->set_title("Лучшие практики");
$PAGE->set_heading("Лучшие практики");

$PAGE->requires->css(new moodle_url('/lib/tablesorter/css/theme.bootstrap_4.min.css'));
$PAGE->requires->css(new moodle_url('/lib/tablesorter/css/theme.default.min.css'));

echo $OUTPUT->header();
if(is_siteadmin()) {
    $addbtn = \html_writer::start_tag('div', array('style' => 'text-align: right'));
    $addbtn .= \html_writer::link(new \moodle_url('/local/faqwiki/adding.php', ['action' => 'add']), 'Добавить <i class="fa fa-plus-circle" aria-hidden="true" style="font-family: FontAwesome"></i>',
        array('type' => 'button', 'class' => 'btn btn-outline-primary'));
        //'target' => '_blank',
    $addbtn .= \html_writer::end_tag('div');
    echo $addbtn;
}

$data = $DB->get_records_sql("SELECT * FROM mdl_faqwiki ORDER BY timemodified ASC");
$render = array();
$i = 0;

foreach ($data as $d){
    $params = (array)getParams($d->params);

    $params['content'] = html_entity_decode( $params['content'], null, 'UTF-8');

    $itemlnk = \html_writer::link(new \moodle_url('/local/faqwiki/view.php', ['id' => $d->id]), $d->name,
        array('target' => '_blank'));

    $username = $DB->get_field('user', 'concat(lastname, \' \', firstname) as nameuser', ['id' => $d->usermodified]);
    $userlnk = \html_writer::link(new \moodle_url('/user/profile.php', ['id' => $d->usermodified]), $username,
        array('target' => '_blank'));

    $match[$i] = [
        'name' => $itemlnk,
        'text' => trimString($params['content']),
        'user' => $userlnk,
        'timemodified' => date('d.m.Y H:i:s', $d->timemodified)
    ];

    if(is_siteadmin()){
        $editlnk = \html_writer::link(new \moodle_url('/local/faqwiki/adding.php', ['action' => 'edit', 'id' => $d->id]), '<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>');
//        $dellnk = \html_writer::link(new \moodle_url('/local/faqwiki/adding.php', ['action' => 'del']), '<i class="fa fa-trash-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>');

        $dellnk = \html_writer::link(
            new \moodle_url('/local/faqwiki/deleteitem.php', ['id' => $d->id, 'action' => 'deleteitem']),
            '<i class="fa fa-trash-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>',
            [
                'style' => 'margin-left: 10px;',
                'onclick' => 'return confirm("Действительно удалить?");'
            ]
        );

        $match[$i]['editlnk'] = $editlnk;
        $match[$i]['dellnk'] = $dellnk;
    }

    $i++;
}
$render = ['match' => $match];

echo $OUTPUT->render_from_template("local_faqwiki/faqwiki", $render);




$PAGE->requires->js_call_amd('local_alumni/script', 'init');

echo $OUTPUT->footer();




?>