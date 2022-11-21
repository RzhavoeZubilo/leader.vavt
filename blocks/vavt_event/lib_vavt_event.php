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
        $addbtn .= \html_writer::link(new \moodle_url('/blocks/vavt_event/adding.php', ['action' => 'add']), 'Добавить мероприятие <i class="fa fa-plus-circle" aria-hidden="true" style="font-family: FontAwesome"></i>',
            array('type' => 'button', 'class' => 'btn btn-outline-primary'));
        //'target' => '_blank',
        $addbtn .= \html_writer::end_tag('div');

    }

    $render = array();
    $i = $monthevent = 0;

    foreach ($data as $d) {
        $_monthsList = array(
            "1"=>"Январь","2"=>"Февраль","3"=>"Март",
            "4"=>"Апрель","5"=>"Май", "6"=>"Июнь",
            "7"=>"Июль","8"=>"Август","9"=>"Сентябрь",
            "10"=>"Октябрь","11"=>"Ноябрь","12"=>"Декабрь");

        $month = mb_strtoupper($_monthsList[date('n',$d->dateevent)]);

        if(date('n',$d->dateevent) <> $monthevent){
            $montblock = $month.'‘'.date('Y',$d->dateevent);
            $monthevent = date('n',$d->dateevent);
        }else $montblock = '';

        $params = (array)getParams($d->params);

        $params['content'] = html_entity_decode($params['content'], null, 'UTF-8');

        $cntevent = $DB->count_records('block_vavt_event');

        $readlnk = \html_writer::link(new \moodle_url('/blocks/vavt_event/view.php', ['id' => $d->id]), 'Подробнее...',
            array('style' => 'font-weight: 800'));

        if ($typeevent == 'block') {
            $itemlnk = $d->name;
            $dateevent = $month.'‘'.date('Y',$d->dateevent);
        } else {
            $itemlnk = \html_writer::link(new \moodle_url('/blocks/vavt_event/view.php', ['id' => $d->id]), $d->name);
            $dateevent = $month.'‘'.date('Y',$d->dateevent);
        }

        // picture
        // $fromid = $DB->get_field('block', 'id', ['name'=>'vavt_event']);
        //$fromid = $DB->get_field('block_instances', 'id', ['blockname'=>'vavt_event'], 1);
        $context = context_system::instance();

        $fs = get_file_storage();

        $picture = null;

        $files = $fs->get_directory_files(
            $context->id, 'block_vavt_event',
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
                'block_vavt_event',
                'pictures',
                $d->id
            );
//            $this->config->text = file_rewrite_pluginfile_urls($this->config->text,
//                'pluginfile.php',
//                $this->context->id,
//                'block_html',
//                'content',
//                NULL);

            $params['picture'] = true;
        } else $imgsrc = $CFG->wwwroot.'/blocks/vavt_event/templates/itemimg.png';

        $match[$i] = [
            'eventid' => $d->id,
            'name' => $itemlnk,
            'text' => trimString($params['content']),
            'dateevent' => $dateevent,
            'readlnk' => $readlnk,
            'imgsrc' => $imgsrc,
            'montblock' => $montblock
        ];

        if (is_siteadmin()) {
            $editlnk = \html_writer::link(new \moodle_url('/blocks/vavt_event/adding.php', ['action' => 'edit', 'id' => $d->id]), '<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>');
            //        $dellnk = \html_writer::link(new \moodle_url('/local/faqwiki/adding.php', ['action' => 'del']), '<i class="fa fa-trash-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>');

            $dellnk = \html_writer::link(
                new \moodle_url('/blocks/vavt_event/deleteitem.php', ['id' => $d->id, 'action' => 'deleteitem', 'contextid'=>$context->id]),
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

            if($DB->record_exists('vavt_favorite', ['usermodified' => $USER->id,  'nameplugin' => 'event', 'objid'=>$d->id])){
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
    $string = strip_tags($string);
    $string = substr($string, 0, 250);
    $string = rtrim($string, "!,.-");
    $string = substr($string, 0, strrpos($string, ' '));
    return $string . "...";
}