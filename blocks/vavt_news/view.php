<?php
/**
 * User: densh
 * Date: 28.02.2022
 * Time: 16:01
 */

global $DB, $PAGE, $CFG, $OUTPUT;

require_once('../../config.php');
require_once($CFG->libdir . '/filelib.php');
require_once('lib_vavt_news.php');

$id = optional_param('id', '0', PARAM_INT);

$PAGE->set_url('/blocks/vavt_news/view.php');
$PAGE->set_title("Новости");

$data = $DB->get_record("block_vavt_news",  ['id' => $id]);

$PAGE->set_heading($data->name);
$PAGE->navbar->add('Новости', new \moodle_url('/blocks/vavt_news/index.php'));

echo $OUTPUT->header();


$params = (array)getParams($data->params);

$params['content'] = html_entity_decode( $params['content'], null, 'UTF-8');

$username = $DB->get_field('user', 'concat(lastname, \' \', firstname) as nameuser', ['id' => $data->usermodified]);
$userlnk = \html_writer::link(new \moodle_url('/user/profile.php', ['id' => $data->usermodified]), $username,
    array('target' => '_blank'));

/////////////////////////
// picture
$fromid = $DB->get_field('block', 'id', ['name'=>'vavt_news']);
$context = context_block::instance($fromid);

$fs = get_file_storage();

$picture = null;

$files = $fs->get_directory_files(
    $context->id, 'block_vavt_news',
    'pictures',
    $id,
    '/',
    false,
    false
);
//$files = $fs->get_area_files($context->id, 'mod_longread', 'refimg');

foreach ( $files as $file )
    $picture = $file;

if ( !empty($picture) )
{
    $files = array_pop($files);
    $imgsrc = file_rewrite_pluginfile_urls(
        '@@PLUGINFILE@@/'.$files->get_filename(),
        'pluginfile.php',
        $context->id,
        'block_vavt_news',
        'pictures',
        $id
    );
    $params['picture'] = true;
} else $imgsrc = $CFG->wwwroot.'/blocks/vavt_news/templates/itemimg.png';

$url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename(), false);

/// ////////////////////






$render = [
    'name' => $data->name,
    'text' => $params['content'],
    'user' => $userlnk,
    'timemodified' => date('d.m.Y', $data->timemodified),
    'imgsrc'=>$imgsrc,
    'url'=>$url
];

echo $OUTPUT->render_from_template("block_vavt_news/item", $render);