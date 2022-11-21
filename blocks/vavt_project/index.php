<?php
/**
 * User: densh
 * Date: 08.03.2022
 * Time: 02:03
 */

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once('lib_vavt_project.php');

global $PAGE, $DB, $OUTPUT;

$PAGE->set_url('/blocks/vavt_project/index.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title("Проекты сообщества");
$PAGE->set_heading("Проекты сообщества");

echo $OUTPUT->header();

$data = $DB->get_records_sql("SELECT * FROM mdl_block_vavt_project ORDER BY timemodified DESC");

$render = getContentHTML($data, $typeevent='all');
$context = context_system::instance();

$itemid = get_config('block_vavt_project', 'picture_block_vavt_project');
$img = get_vavt_imgurl($itemid, $context->id, 'block_vavt_project', 'btnadd_picture');
$render['picture_btn_add'] = $img;

$render['btnadd_link'] = get_config('block_vavt_project', 'link_block_vavt_project');

echo $OUTPUT->render_from_template("block_vavt_project/all_project", $render);


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