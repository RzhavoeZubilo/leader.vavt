<?php
/**
 * User: densh
 * Date: 28.02.2022
 * Time: 14:27
 */

global $CFG, $DB, $PAGE, $OUTPUT, $USER;

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$PAGE->set_url('/local/faqwiki/adding.php');

$PAGE->set_title("Лучшие практики");
$PAGE->set_heading("Лучшие практики");

echo $OUTPUT->header();


require_once('form/item.php');
require_once('lib_faqwiki.php');

$mform = new item();

$redirectLink = '/local/faqwiki/index.php';

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

    if($data->action == 'edit'){
        $ob->id = $data->itemid;
        $DB->update_record('faqwiki', $ob);
    }else{
        echo 'ADD';
        $itemid = $DB->insert_record('faqwiki', $ob);
    }

    redirect($redirectLink, '', 1);

//    // picture
//    file_save_draft_area_files(
//        $data->picture,
//        $context->id,
//        'mod_longread',
//        'pictures',
//        $itemid,
//        ['subdirs' => 0]
//    );
//    $quote->set('params', serialize($data));
//    $quote->update();

} else {
    $id = optional_param('id', '0', PARAM_INT);
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.
    $toform = $DB->get_record_sql("SELECT * FROM mdl_faqwiki WHERE id = {$id}");
    $params = (array)getParams($toform->params);
    $toform->paragraph['text'] = html_entity_decode( $params['content'], null, 'UTF-8');
    $toform->paragraph['format'] = 1;

    $toform->itemid = $id;
    $toform->action = optional_param('action', '', PARAM_TEXT);
//    $toform->action = 'edit';

//    $toform->paragraph['itemid'] = ???;

    unset($toform->params);

    //Set default data (if any)
    $mform->set_data($toform);
    //displays the form
    $mform->display();
}

echo $OUTPUT->footer();