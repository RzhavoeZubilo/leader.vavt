<?php
/**
 * User: densh
 * Date: 07.03.2022
 * Time: 00:44
 */


class block_vavt_ref_company extends block_base
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
        $this->title = get_string('vavt_ref_company', 'block_vavt_ref_company');
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.

    public function get_content() {
        global $DB, $OUTPUT,$CFG;
        require_once($CFG->libdir . '/filelib.php');
//        if (! empty($this->config->text)) {
//            $this->content->text = $this->config->text;
//        }
//        if ($this->content !== null) {
//            return $this->content;
//        }

        $cntnews = $DB->count_records('block_vavt_ref_company');
//        $cntnews = count((array)$data);
//        $this->title = $this->config->title."({$cntnews})";

        $this->content         =  new stdClass;
//        $this->content->text   = 'The content of our vavt_ref_company block!';

        // footer блока
        // $this->content->footer = 'Footer here...';


//        echo 'Содержимое для вывода в блоке не задано. Блок нужно скрыть';

        $mysetting = get_config("block_vavt_ref_company");

        $data = $DB->get_records_sql("SELECT * FROM mdl_block_vavt_ref_company ORDER BY timemodified DESC LIMIT 3");

        require_once('lib_vavt_ref_company.php');
        $render = getContentHTML($data, $typenews = 'block');

        $btnallnews = \html_writer::start_tag('div', array());
        $btnallnews .= \html_writer::link(new \moodle_url('/blocks/vavt_ref_company/index.php'), 'Все предприятия',
    array('type' => 'button', 'class' => 'btn btn-outline-primary', 'target' => '_blank'));
        $btnallnews .= \html_writer::end_tag('div');

        $render['cntnews'] = $cntnews;
        $render['btnallnews'] = $btnallnews;

        $this->content->text = $OUTPUT->render_from_template("block_vavt_ref_company/ref_company_block", $render);


        return $this->content;
    }



//    public function specialization() {
//        if (isset($this->config)) {
//            if (empty($this->config->title)) {
//                $this->title = get_string('defaulttitle', 'block_vavt_ref_company');
//            } else {
//                $this->title = $this->config->title;
//            }
//            $data = $DB->get_records_sql("SELECT * FROM mdl_block_vavt_ref_company ORDER BY timemodified DESC");
//
////            if (empty($this->config->text)) {
////                $this->config->text = get_string('defaulttext', 'block_vavt_ref_company');
////            }
//        }
//    }
}