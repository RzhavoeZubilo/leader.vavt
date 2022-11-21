<?php
/**
 * User: densh
 * Date: 28.02.2022
 * Time: 16:01
 */

global $DB, $PAGE, $CFG, $OUTPUT;

require_once('../../config.php');
require_once($CFG->libdir . '/filelib.php');
require_once('lib_vavt_event.php');

$id = optional_param('id', '0', PARAM_INT);

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/blocks/vavt_event/view.php');
$PAGE->set_title("Новости");

$data = $DB->get_record("block_vavt_event",  ['id' => $id]);

$PAGE->set_heading($data->name);
$PAGE->navbar->add('Новости', new \moodle_url('/blocks/vavt_event/index.php'));

echo $OUTPUT->header();

$fromid = $DB->get_field('block_instances', 'id', ['blockname'=>'vavt_event'],1);
$context = $context = context_system::instance();

$params = (array)getParams($data->params);

$params['content'] = html_entity_decode( $params['content'], null, 'UTF-8');
$params['content'] = file_rewrite_pluginfile_urls($params['content'], 'pluginfile.php', $context->id, 'block_vavt_event', 'content', $id);


$username = $DB->get_field('user', 'concat(firstname, \' \', lastname) as nameuser', ['id' => $data->usermodified]);
$userlnk = \html_writer::link(new \moodle_url('/user/profile.php', ['id' => $data->usermodified]), $username,
    array('target' => '_blank'));

/////////////////////////
// picture

$fs = get_file_storage();

$picture = null;

// ищем файлы в заданной filearea
//$files = $fs->get_area_files($context->id, 'block_vavt_event', 'pictures');

// ищем файлы в заданной директории по itemid
$files = $fs->get_directory_files(
    $context->id, 'block_vavt_event',
    'pictures',
    $id,
    '/',
    false,
    false
);

foreach ( $files as $file )
    $picture = $file;

// первый способ получения ссылки на файл
if ( !empty($picture) )
{
    $files = array_pop($files);
    $imgsrc = file_rewrite_pluginfile_urls(
        '@@PLUGINFILE@@/'.$files->get_filename(),
        'pluginfile.php',
        $context->id,
        'block_vavt_event',
        'pictures',
        $id
    );
    $params['picture'] = true;
} else
    // заглушка, если картинка не была добавлена
    $imgsrc = $CFG->wwwroot.'/blocks/vavt_event/templates/itemimg.png';

// второй способ получения ссылки на файл
//$imgsrc = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename(), false);

/////////////////////////
///
$render = [
    'name' => $data->name,
    'text' => $params['content'],
    'user' => $userlnk,
    'timemodified' => date('d.m.Y', $data->timemodified),
    'imgsrc'=>$imgsrc
];

echo $OUTPUT->render_from_template("block_vavt_event/item", $render);

echo $OUTPUT->footer();