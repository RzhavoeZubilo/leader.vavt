<?php
/**
 * User: densh
 * Date: 08.03.2022
 * Time: 12:37
 */

function getContentHTML($data, $typenews)
{
    global $DB, $USER, $CFG;
    require_once($CFG->libdir . '/filelib.php');
    if (is_siteadmin()) {
        $addbtn = \html_writer::start_tag('div', array('style' => 'text-align: right'));
        $addbtn .= \html_writer::link(new \moodle_url('/blocks/vavt_news/adding.php', ['action' => 'add']), 'Добавить новость<i class="fa fa-plus-circle" aria-hidden="true" style="font-family: FontAwesome"></i>',
            array('type' => 'button', 'class' => 'btn btn-outline-primary'));
        //'target' => '_blank',
        $addbtn .= \html_writer::end_tag('div');

    }

    $render = array();
    $i = 0;

    foreach ($data as $d) {
        $params = (array)getParams($d->params);

        $params['content'] = html_entity_decode($params['content'], null, 'UTF-8');

        $cntnews = $DB->count_records('block_vavt_news');

        $readlnk = \html_writer::link(new \moodle_url('/blocks/vavt_news/view.php', ['id' => $d->id]), 'ЧИТАТЬ НОВОСТЬ',
            array('target' => '_blank', 'style' => 'font-weight: 800'));

        if ($typenews == 'block') {
            $itemlnk = $d->name;
            $datenews = date('d.m.Y', $d->timemodified);
            $username = $DB->get_field('user', 'concat(lastname, \' \', split_part(firstname, \' \', 1)) as nameuser', ['id' => $d->usermodified]);
        } else {
            $itemlnk = \html_writer::link(new \moodle_url('/blocks/vavt_news/view.php', ['id' => $d->id]), $d->name,
                array('target' => '_blank'));
            $datenews = date('d.m.Y H:i:s', $d->timemodified);
            $username = $DB->get_field('user', 'concat(lastname, \' \', firstname) as nameuser', ['id' => $d->usermodified]);
        }

        $userlnk = \html_writer::link(new \moodle_url('/user/profile.php', ['id' => $d->usermodified]), $username,
            array('target' => '_blank'));

        // picture
        $fromid = $DB->get_field('block', 'id', ['name'=>'vavt_news']);
        $context = context_block::instance($fromid);

        $fs = get_file_storage();

        $picture = null;

        $files = $fs->get_directory_files(
            $context->id, 'block_vavt_news',
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
                'block_vavt_news',
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
        } else $imgsrc = $CFG->wwwroot.'/blocks/vavt_news/templates/itemimg.png';

        $match[$i] = [
            'name' => $itemlnk,
            'text' => trimString($params['content']),
            'user' => $userlnk,
            'timemodified' => $datenews,
            'readlnk' => $readlnk,
            'imgsrc' => $imgsrc
        ];

        if (is_siteadmin()) {
            $editlnk = \html_writer::link(new \moodle_url('/blocks/vavt_news/adding.php', ['action' => 'edit', 'id' => $d->id]), '<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>');
            //        $dellnk = \html_writer::link(new \moodle_url('/local/faqwiki/adding.php', ['action' => 'del']), '<i class="fa fa-trash-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>');

            $dellnk = \html_writer::link(
                new \moodle_url('/blocks/vavt_news/deleteitem.php', ['id' => $d->id, 'action' => 'deleteitem']),
                '<i class="fa fa-trash-o" aria-hidden="true" style="font-family: FontAwesome; margin-left: 15px;"></i>',
                [
                    'style' => 'margin-left: 10px;',
                    'onclick' => 'return confirm("Действительно удалить?");'
                ]
            );

            $match[$i]['editlnk'] = $editlnk;
            $match[$i]['dellnk'] = $dellnk;
        }

        $i++;
    }
    $render = ['match' => $match, 'addbtn' => $addbtn, 'cntnews' => $cntnews];

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