<?php
/**
 * User: densh
 * Date: 08.03.2022
 * Time: 12:37
 */

function getContentHTML($data, $typeevent)
{
    global $DB, $USER, $CFG;
    require_once($CFG->libdir . '/filelib.php');
    if (is_siteadmin()) {
        $addbtn = \html_writer::start_tag('div', array('style' => 'text-align: right'));
        $addbtn .= \html_writer::link(new \moodle_url('/blocks/vavt_project/adding.php', ['action' => 'add']), 'Добавить проект <i class="fa fa-plus-circle" aria-hidden="true" style="font-family: FontAwesome"></i>',
            array('type' => 'button', 'class' => 'btn btn-outline-primary'));
        //'target' => '_blank',
        $addbtn .= \html_writer::end_tag('div');

    }

    $render = array();
    $i = $monthevent = 0;

    foreach ($data as $d) {

        $params = (array)getParams($d->params);

        $params['content'] = html_entity_decode($params['content'], null, 'UTF-8');

        $cntevent = $DB->count_records('block_vavt_project');

        $readlnk = \html_writer::link(new \moodle_url('/blocks/vavt_project/view.php', ['id' => $d->id]), 'ПОДРОБНЕЕ',
            array('style' => 'font-weight: 800'));

        if ($typeevent == 'block') {
            $itemlnk = $d->name;
            $dateevent = $month.'‘'.date('Y',$d->dateevent);
        } else {
            $itemlnk = \html_writer::link(new \moodle_url('/blocks/vavt_project/view.php', ['id' => $d->id]), $d->name);
            $dateevent = $month.'‘'.date('Y',$d->dateevent);
        }

        $context = context_system::instance();

        $fs = get_file_storage();

        $picture = null;

        $files = $fs->get_directory_files(
            $context->id, 'block_vavt_project',
            'pictures',
            $d->id,
            '/',
            false,
            false
        );

        foreach ( $files as $file )
            $picture = $file;

        if ( !empty($picture) )
        {
            $files = array_pop($files);
            $imgsrc = file_rewrite_pluginfile_urls(
                '@@PLUGINFILE@@/'.$files->get_filename(),
                'pluginfile.php',
                $context->id,
                'block_vavt_project',
                'pictures',
                $d->id
            );

            $params['picture'] = true;
        } else $imgsrc = $CFG->wwwroot.'/blocks/vavt_project/templates/itemimg.png';

        $match[$i] = [
            'eventid' => $d->id,
            'name' => $itemlnk,
            'text' => trimString($params['content']),
            'dateevent' => $dateevent,
            'readlnk' => $readlnk,
            'imgsrc' => $imgsrc
        ];

        if (is_siteadmin()) {
            $editlnk = \html_writer::link(new \moodle_url('/blocks/vavt_project/adding.php', ['action' => 'edit', 'id' => $d->id]), '<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>');
            $dellnk = \html_writer::link(
                new \moodle_url('/blocks/vavt_project/deleteitem.php', ['id' => $d->id, 'action' => 'deleteitem', 'contextid'=>$context->id]),
                '<i class="fa fa-trash-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>',
                [
                    'style' => 'margin-left: 10px;',
                    'onclick' => 'return confirm("Действительно удалить?");'
                ]
            );
            if($i == 0){
                $match[$i]['firstblock'] = true;
            }
            $match[$i]['editlnk'] = $editlnk;
            $match[$i]['dellnk'] = $dellnk;

            if($DB->record_exists('vavt_favorite', ['usermodified' => $USER->id,  'nameplugin' => 'project', 'objid'=>$d->id])){
                $match[$i]['has_addfav'] = 'addfav';
            }
        }

        $i++;
    }
    $render = ['match' => $match, 'cntevent' => $cntevent];
    if(isset($addbtn) && !empty($addbtn)) $render['addbtn'] = $addbtn;

    return $render;
}

function getParams($param)
{
    if ($param) {
        if (unserialize($param) == false) {
            $str = $param;
            $str = preg_replace_callback('!s:(\d+):"(.*?)";! s', function ($match) {
                return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
            }, $str);
            $params = unserialize($str);
        } else {
            $params = unserialize($param);
        }
        return $params;
    }
    return null;
}

function trimString($string)
{
    $defstr = $string;
    $string = strip_tags($string);
    $string = substr($string, 0, 250);
    $string = rtrim($string, "!,.-");
    if(strlen($string) >= 250){
        $string = substr($string, 0, strrpos($string, ' '));
        return $string . "...";
    }else{
        return $defstr;
    }

}