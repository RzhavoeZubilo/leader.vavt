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

$PAGE->set_url('/blocks/vavt_ref_company/adding.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title("Витрина предприятий");
$PAGE->set_heading("Добавление предприятия");

echo $OUTPUT->header();


require_once('form/company_item.php');
require_once('lib_vavt_ref_company.php');

const EDITOR_OPTIONS = [
    'changeformat' => 1,
    'noclean'      => 0,
    'trusttext'    => true,
    'subdirs' => true,
    'maxfiles' => EDITOR_UNLIMITED_FILES,
    'accepted_types' => ['.jpg', '.jpeg', '.png']
];

$mform = new company_item();

$redirectLink = $CFG->wwwroot.'/blocks/vavt_ref_company/index.php';

$fromid = $DB->get_field('block_instances', 'id', ['blockname'=>'vavt_ref_company'],1);
$context = context_block::instance($fromid);

//Здесь производится обработка и отображение формы
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect(new moodle_url($redirectLink));
} else if ($data = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.

    if($data->action === 'edit'){
        save($data, $context);
    } else {
        create($data, $context);
    }

     redirect($redirectLink, '', 100);

} else {
    $id = optional_param('id', '0', PARAM_INT);
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.

    $toform = $DB->get_record_sql("SELECT * FROM mdl_block_vavt_ref_company WHERE id = {$id}");

    $toform->itemid = $id;
    $toform->action = optional_param('action', '', PARAM_TEXT);

    $params = (array)getParams($toform->params);

    if($toform->action === 'edit'){
        // picture
        $draftitemid = file_get_submitted_draft_itemid('pictures');

        file_prepare_draft_area(
            $draftitemid,
            $context->id,
            'block_vavt_ref_company',
            'pictures',
            $id,
            ['subdirs' => 0]
        );

        $toform->picture = $draftitemid;

        // text
        $text = html_entity_decode( $params['content'], null, 'UTF-8');
        $draftitemid = file_get_submitted_draft_itemid('paragraph');
        $text = file_prepare_draft_area($draftitemid, $context->id, 'block_vavt_ref_company', 'content', $id, EDITOR_OPTIONS, $text);

        $mas = [
            'format' => 1,
            'text' => $text,
            'itemid' => $draftitemid
        ];

        $toform->paragraph = $mas;
    }

//    unset($toform->params);

    //Set default data (if any)
    $mform->set_data($toform);
    //displays the form
    $mform->display();

}

function create($data, $context)
{
    global $DB;
// text
    $paragraph = $data->paragraph;
    unset($data->paragraph);
    $data->content = htmlentities($paragraph['text'], null, 'UTF-8');

    $ob = (object)[
        'name' => $data->name,
        'params' => serialize($data),
        'tagregion'=> $data->tagregion,
        'tagindustry'=> $data->tagindustry,
        'tagcategory'=> $data->tagcategory,
        'sitelink'=> $data->sitelink,
        'contact'=> $data->contact,
        'sortorder' => 0,
        'usermodified' => $data->usermodified,
        'timemodified' => $data->timemodified
    ];

    $itemid = $DB->insert_record('block_vavt_ref_company', $ob);

    $draftitemid = $paragraph['itemid'];
    $data->content = file_save_draft_area_files($draftitemid, $context->id, 'block_vavt_ref_company', 'content', $itemid, EDITOR_OPTIONS, $data->content);

    // picture

    file_save_draft_area_files(
        $data->picture,
        $context->id,
        'block_vavt_ref_company',
        'pictures',
        $itemid,
        ['subdirs' => 0]
    );


    $ob->id = $itemid;
    $ob->params = serialize($data);
    $DB->update_record('block_vavt_ref_company', $ob);

}
function save($data, $context)
{
    global $DB;

    // text
    $paragraph = $data->paragraph;
    unset($data->paragraph);
    $data->content = htmlentities($paragraph['text'], null, 'UTF-8');

    $ob = (object)[
        'name' => $data->name,
        'params' => serialize($data),
        'tagregion'=> $data->tagregion,
        'tagindustry'=> $data->tagindustry,
        'tagcategory'=> $data->tagcategory,
        'sitelink'=> $data->sitelink,
        'contact'=> $data->contact,
        'sortorder' => 0,
        'usermodified' => $data->usermodified,
        'timemodified' => $data->timemodified
    ];

    $ob->id = $data->itemid;
    $DB->update_record('block_vavt_ref_company', $ob);
    $itemid = $data->itemid;

    // $fromid = $DB->get_field('block', 'id', ['name'=>'vavt_ref_company']);

    $draftitemid = $paragraph['itemid'];
    $data->content = file_save_draft_area_files($draftitemid, $context->id, 'block_vavt_ref_company', 'content', $itemid, EDITOR_OPTIONS, $data->content);


    // picture
//    file_prepare_draft_area($draftitemid, $context->id, 'block_vavt_ref_company', 'pictures', $itemid, array('subdirs' => true));

    file_save_draft_area_files(
        $data->picture,
        $context->id,
        'block_vavt_ref_company',
        'pictures',
        $itemid,
        ['subdirs' => 0]
    );

    $ob->params = serialize($data);
    $DB->update_record('block_vavt_ref_company', $ob);

    print_object($data);

}

echo $OUTPUT->footer();