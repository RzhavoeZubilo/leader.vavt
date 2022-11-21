<?php
///**
// * User: densh
// * Date: 07.03.2022
// * Time: 00:44
// */
//
//
class block_vavt_event extends block_base
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
        $this->title = get_string('vavt_event', 'block_vavt_event');
    }

    public function get_content() {
        global $DB, $OUTPUT,$CFG;
        require_once($CFG->libdir . '/filelib.php');

        $this->title = $this->config->title;

        $this->content         =  new stdClass;
        $this->content->text   = 'The content of our vavt_event block!';
         $this->content->footer = 'Footer here...';

        return $this->content;
    }

    function _self_test() {
        return true;
    }

}

// eventitems
// eventitemsnumber