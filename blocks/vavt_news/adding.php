<?php
/**
 * User: densh
 * Date: 28.02.2022
 * Time: 14:27
 */

global $CFG, $DB, $PAGE, $OUTPUT, $USER;

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir.'/filelib.php');

$PAGE->set_url('/blocks/vavt_news/adding.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title("Новости");
$PAGE->set_heading("Добавление новости");

echo $OUTPUT->header();


require_once('form/news_item.php');
require_once('lib_vavt_news.php');

const EDITOR_OPTIONS = [
    'changeformat' => 1,
    'noclean'      => 0,
    'trusttext'    => 0,

    'subdirs' => 0,
    'maxfiles' => EDITOR_UNLIMITED_FILES,
    'accepted_types' => ['.jpg', '.jpeg', '.png']
];

$mform = new news_item();

$redirectLink = $CFG->wwwroot.'/blocks/vavt_news/index.php';

//Здесь производится обработка и отображение формы
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect(new moodle_url($redirectLink));
} else if ($data = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.

    $paragraph = $data->paragraph;
    unset($data->paragraph);
    $data->content = htmlentities($paragraph['text'], null, 'UTF-8');

    $ob = (object)[
        'name' => $data->name,
        'params' => serialize($data),
        'sortorder' => 0,
        'usermodified' => $data->usermodified,
        'timemodified' => $data->timemodified
    ];

    if($data->action == 0){
        $itemid = $DB->insert_record('block_vavt_news', $ob);
        $ob->id = $itemid;
    } else {
        $ob->id = $data->itemid;
        $DB->update_record('block_vavt_news', $ob);
        $itemid = $data->itemid;
    }

    $draftitemid = $paragraph['itemid'];
    // $fromid = $DB->get_field('block', 'id', ['name'=>'vavt_news']);
    $fromid = $DB->get_field('block_instances', 'id', ['blockname'=>'vavt_news'],1);
    $context = context_block::instance($fromid);


    // picture
    file_prepare_draft_area($draftitemid, $context->id, 'block_vavt_news', 'pictures', $itemid, array('subdirs' => true));

    file_save_draft_area_files(
        $data->picture,
        $context->id,
        'block_vavt_news',
        'pictures',
        $itemid,
        ['subdirs' => 0]
    );

    $ob->params = serialize($data);
    $DB->update_record('block_vavt_news', $ob);

     redirect($redirectLink, '', 100);

} else {
    $id = optional_param('id', '0', PARAM_INT);
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.
    $toform = $DB->get_record_sql("SELECT * FROM mdl_block_vavt_news WHERE id = {$id}");
    $params = (array)getParams($toform->params);
    $toform->paragraph['text'] = html_entity_decode( $params['content'], null, 'UTF-8');
    $toform->paragraph['format'] = 1;

    $toform->itemid = $id;
    $toform->action = optional_param('action', '', PARAM_TEXT);

    unset($toform->params);

    //Set default data (if any)
    $mform->set_data($toform);
    //displays the form
    $mform->display();

}

echo $OUTPUT->footer();