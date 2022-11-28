<?php
/**
 * User: densh
 * Date: 08.03.2022
 * Time: 02:03
 */

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once('lib_vavt_event.php');

require_login();

$PAGE->set_url('/blocks/vavt_event/index.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title("Мероприятия");
$PAGE->set_heading("Мероприятия");

echo $OUTPUT->header();


$data = $DB->get_records_sql("SELECT * FROM mdl_block_vavt_event ORDER BY dateevent DESC");

$render = getContentHTML($data, $typeevent='all');
$context = context_system::instance();

$itemid = get_config('block_vavt_event', 'picture_block_event');
$img = get_vavt_imgurl($itemid, $context->id, 'block_vavt_event', 'event_picture');
$render['picture_block_event'] = $img;

echo $OUTPUT->render_from_template("block_vavt_event/all_event", $render);


echo $OUTPUT->footer();



function get_vavt_imgurl($id, $contextid = 1, $component = 'local_edu', $filearea = 'edu_mvp')
{

    $fs = get_file_storage();
    $files = $fs->get_area_files($contextid, $component, $filearea, $id);
    $url = '';
    foreach ($files as $file) {
        $name = $file->get_filename();
        if ($name == '.') continue;
        $url = \moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
    }
    $u = $mas['url'] = (string)$url;

    return $u;
}