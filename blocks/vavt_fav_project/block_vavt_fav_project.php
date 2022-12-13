<?php
/**
 * User: densh
 * Date: 07.03.2022
 * Time: 00:44
 */

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

class block_vavt_fav_project extends block_base
{
    private $headerhidden = true;

    // включаем глобальную настройку из settings.php
    function has_config() {
        return true;
    }

    //function hide_header() {
    //    return $this->headerhidden;
    //}

    public function init()
    {
        $this->title = get_string('pluginname', 'block_vavt_fav_project');
    }

    // получаем имя блока из настроек блока
    public function specialization() {
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_vavt_fav_project');
        } else {
            $this->title = $this->config->title;
        }
    }

    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.

    public function get_content() {
        global $DB, $OUTPUT,$CFG, $USER;

        $context = context_system::instance();

        $this->content         =  new stdClass;

        $render = array();
        $eventinfo = array();

        $data = $DB->get_records_sql("SELECT * FROM mdl_vavt_favorite WHERE usermodified = {$USER->id} AND nameplugin = 'project'");

        if(!empty($data)){
            foreach ($data as $d){
                $evt = array();
                $event = $DB->get_record('block_vavt_project', ['id'=>$d->objid]);

                $params = (array)self::getParams($event->params);

                if($imgsrc = self::get_vavt_imgurl($event->id, $context->id, 'block_vavt_project', 'pictures')){

                } else $imgsrc = $CFG->wwwroot.'/blocks/vavt_project/templates/itemimg.png';

                $evt['lnkevt'] = "/blocks/vavt_project/view.php?id=$event->id";
                $evt['pic'] = $imgsrc;
                $evt['name'] = $params['name'];

                $eventinfo[]=$evt;
            }

            $render['arrevent'] =  $eventinfo;
            $render['hasdata'] =  true;
        }else{
            $render['hasdata'] =  false;
        }

        $this->content->text = $OUTPUT->render_from_template("block_vavt_fav_project/item", $render);


        return $this->content;
    }

    public function get_vavt_imgurl($id, $contextid = 1, $component = 'block_vavt_event', $filearea = 'edu_mvp')
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
    public function getParams($param)
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
}