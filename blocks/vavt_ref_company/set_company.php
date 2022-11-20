<?php
/**
 * User: densh
 * Date: 28.04.2022
 * Time: 03:23
 */

global $DB, $PAGE, $CFG, $OUTPUT;

require_once('../../config.php');
require_once($CFG->libdir . '/filelib.php');
require_once('lib_vavt_ref_company.php');

$id = optional_param('id', '0', PARAM_INT);
$action = optional_param('action', 'view', PARAM_TEXT);

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/blocks/vavt_ref_company/set_company.php');
$PAGE->set_title("Подтверждение предприятий");

$PAGE->set_heading('Подтверждение предприятий');
$PAGE->navbar->add('Витрина предприятий', new \moodle_url('/blocks/vavt_ref_company/index.php'));

echo $OUTPUT->header();


//$row[] = new tabobject('view', new moodle_url($PAGE->url, ['action' => 'view']), "Мои организации");
$row[] = new tabobject('view', new moodle_url('/blocks/vavt_ref_company/set_company.php', ['action' => 'view']), "Мои организации");
$row[] = new tabobject('edit', new moodle_url('/blocks/vavt_ref_company/confirm_company.php', ['action' => 'edit']), "Подверждение");

echo $OUTPUT->tabtree($row, $action);

$addbtn = \html_writer::start_tag('div', array('style' => 'text-align: right'));
        $addbtn .= \html_writer::link(new \moodle_url('/blocks/vavt_ref_company/adding.php', ['action' => 'add']), 'Добавить предприятие<i class="fa fa-plus-circle" aria-hidden="true" style="font-family: FontAwesome"></i>',
            array('type' => 'button', 'class' => 'btn btn-outline-primary')
);
echo $addbtn;

$data = $DB->get_records("block_vavt_ref_company",[],'id DESC');

echo <<<HTML
<table class="table generaltable">
<thead>
<tr>
<th>Предприятие</th>
<th>Дата добавления</th>
<th>Статус</th>
<th>Действие</th>
</tr>
</thead>
<tbody>


HTML;

foreach ($data as $dat){
    $timemodified = date('d.m.Y', $dat->timemodified);
    if($dat->confirm == 1) {
        $timeconf = date('d.m.Y', $dat->timeconfirm);
        $conf = 'Да <br>'.$timeconf;
        $btn = '<button type="button" class="btn btn-danger">Отклонить</button>';
    }
    else {
        $conf = 'Нет';
        $btn = '<button type="button" class="btn btn-success">Принять</button>';

    }

    if (is_siteadmin()) {
        $fromid = $DB->get_field('block_instances', 'id', ['blockname'=>'vavt_ref_company'], 1);
        $context = context_block::instance($fromid);

        $editlnk = \html_writer::link(new \moodle_url('/blocks/vavt_ref_company/adding.php', ['action' => 'edit', 'id' => $dat->id]), '<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>');
        //        $dellnk = \html_writer::link(new \moodle_url('/local/faqwiki/adding.php', ['action' => 'del']), '<i class="fa fa-trash-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>');

        $dellnk = \html_writer::link(
            new \moodle_url('/blocks/vavt_ref_company/deleteitem.php', ['id' => $dat->id, 'action' => 'deleteitem', 'contextid'=>$context->id]),
            '<i class="fa fa-trash-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>',
            [
                'style' => 'margin-left: 10px;',
                'onclick' => 'return confirm("Действительно удалить?");'
            ]
        );
    }

    echo <<<STR
    <tr>
<td><a href="https://community-lp.vavt.ru/blocks/vavt_ref_company/view.php?id=$dat->id">$dat->name</a></td>
<td>$timemodified</td>
<td>$conf</td>
<td>$editlnk  $dellnk</td>
</tr>
STR;

}


echo <<<HTML
</tbody>
</table>
HTML;


echo $OUTPUT->footer();