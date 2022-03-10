<?php
/**
 * User: densh
 * Date: 07.03.2022
 * Time: 00:44
 */


class block_vavt_contact extends block_base
{
    private $headerhidden = true;

    // включаем глобальную настройку из settings.php
    function has_config() {
        return true;
    }

    function hide_header() {
        return $this->headerhidden;
    }

    public function init()
    {
        $this->title = get_string('vavt_contact', 'block_vavt_contact');
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.

    public function get_content() {
        global $DB, $OUTPUT,$CFG, $USER;

//        if (! empty($this->config->text)) {
//            $this->content->text = $this->config->text;
//        }
//        if ($this->content !== null) {
//            return $this->content;
//        }

//        $this->title = $this->config->title."({$cntnews})";

        $this->content         =  new stdClass;
//        $this->content->text   = 'The content of our vavt_news block!';
//        $this->content->footer = 'Footer here...';

        if( $support = explode(",",  $this->config->support)){
            $arrsupport = array();
            foreach ($support as $key => $value){
                $user = $DB->get_record('user', array('id' => $value));
                $userpicture = $OUTPUT->user_picture($user, array('size'=>28));
                $userurl = new moodle_url('/user/view.php', array('id' => $user->id));
                $userlink = html_writer::link($userurl, $userpicture .' '. fullname($user));

                $arrsupport['userpicture'] = $userpicture;
                // todo не выводит имя
                $arrsupport['username'] = stristr(trim($user->firstname), ' ', true).' '.trim($user->lastname);
            }
        }

//        if( $community = explode(",",  $this->config->community)){
//            $arrcommunity = array();
//            foreach ($community as $key => $value){
//                $user = $DB->get_record('user', array('id' => $value));
//                $userpicture = $OUTPUT->user_picture($user, array('size'=>28));
//                $userurl = new moodle_url('/user/view.php', array('id' => $user->id));
//                $userlink = html_writer::link($userurl, $userpicture .' '. fullname($user));
//
//                $arrcommunity['userpicture'] = $userpicture;
//                // todo не выводит имя
//                $arrcommunity['username'] = stristr(trim($user->firstname), ' ', true).' '.trim($user->lastname);
//            }
//        }

        if( $paidprograms = explode(",",  $this->config->paidprograms)){
            $arrpaidprograms = array();
            foreach ($paidprograms as $key => $value){
                $user = $DB->get_record('user', array('id' => $value));
                $userpicture = $OUTPUT->user_picture($user, array('size'=>28));
                $userurl = new moodle_url('/user/view.php', array('id' => $user->id));
                $userlink = html_writer::link($userurl, $userpicture .' '. fullname($user));

                $arrpaidprograms['userpicture'] = $userpicture;
                // todo не выводит имя
                $arrpaidprograms['username'] = stristr(trim($user->firstname), ' ', true).' '.trim($user->lastname);
            }
        }


        $render = array();

//        $render['userpic'] =  $OUTPUT->user_picture($USER, array('size'=>28));

        $render['arrsupport'] =  $arrsupport;
//        $render['arrcommunity'] =  $arrcommunity;
        $render['arrcommunity'] =  '';
        $render['arrpaidprograms'] =  $arrpaidprograms;

        $this->content->text = $OUTPUT->render_from_template("block_vavt_contact/item", $render);


        return $this->content;
    }

//    public function specialization() {
//        if (isset($this->config)) {
//            if (empty($this->config->title)) {
//                $this->title = get_string('defaulttitle', 'block_vavt_contact');
//            } else {
//                $this->title = $this->config->title;
//            }
//            $data = $DB->get_records_sql("SELECT * FROM mdl_block_vavt_contact ORDER BY timemodified DESC");
//
////            if (empty($this->config->text)) {
////                $this->config->text = get_string('defaulttext', 'block_vavt_contact');
////            }
//        }
//    }
}