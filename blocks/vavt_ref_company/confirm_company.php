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
$PAGE->set_url('/blocks/vavt_ref_company/view.php');
$PAGE->set_title("Подтверждение предприятий");

$PAGE->set_heading('Подтверждение предприятий');
$PAGE->navbar->add('Витрина предприятий', new \moodle_url('/blocks/vavt_ref_company/index.php'));

echo $OUTPUT->header();


$data = $DB->get_records("block_vavt_ref_company",[],'id DESC');

$row[] = new tabobject('view', new moodle_url('/blocks/vavt_ref_company/set_company.php', ['action' => 'view']), "Мои организации");
$row[] = new tabobject('edit', new moodle_url('/blocks/vavt_ref_company/confirm_company.php', ['action' => 'edit']), "Подверждение");

echo $OUTPUT->tabtree($row, $action);

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
    echo <<<STR
    <tr>
<td><a href="https://community-lp.vavt.ru/blocks/vavt_ref_company/view.php?id=$dat->id">$dat->name</a></td>
<td>$timemodified</td>
<td>$conf</td>
<td>$btn</td>
</tr>
STR;

}


echo <<<HTML
</tbody>
</table>
HTML;


echo $OUTPUT->footer();