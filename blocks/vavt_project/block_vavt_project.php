<?php

class block_vavt_project extends block_base
{
    private $headerhidden = true;

    // включаем глобальную настройку из settings.php
    function has_config() {
        return true;
    }
    // скрыть заголовок блока
    function hide_header() {
        return $this->headerhidden;
    }

    public function init()
    {
        $this->title = get_string('vavt_project', 'block_vavt_project');
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.

    public function get_content() {
        global $DB, $OUTPUT,$CFG;
        require_once($CFG->libdir . '/filelib.php');

//        $cntevent = $DB->count_records('block_vavt_project');
////        $cntevent = count((array)$data);
        $this->title = $this->config->title;
        $this->content         =  new stdClass;
        $this->content->text = 'Содержимое блока разрабатывается';
        $this->content->footer = 'Footer here...';
//        $this->content         =  new stdClass;
////        $this->content->text   = 'The content of our vavt_project block!';
//
//        // footer блока
//        // $this->content->footer = 'Footer here...';
//
//        $mysetting = get_config("block_vavt_project");
//
//        $data = $DB->get_records_sql("SELECT * FROM mdl_block_vavt_project ORDER BY timemodified DESC LIMIT {$mysetting->cntitem}");
//
//        require_once('lib_vavt_project.php');
//        $render = getContentHTML($data, $typeevent = 'block');
//
//        $btnallevent = \html_writer::start_tag('div', array());
//        $btnallevent .= \html_writer::link(new \moodle_url('/blocks/vavt_project/index.php'), 'ВСЕ НОВОСТИ',
//    array('type' => 'button', 'class' => 'btn btn-outline-primary', 'target' => '_blank'));
//        $btnallevent .= \html_writer::end_tag('div');
//
//        $render['cntevent'] = $cntevent;
//        $render['btnallevent'] = $btnallevent;
//
//        $this->content->text = $OUTPUT->render_from_template("block_vavt_project/event_block", $render);


        return $this->content;
    }

    function _self_test() {
        return true;
    }

}