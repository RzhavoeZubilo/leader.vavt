<?php
/**
 * User: densh
 * Date: 07.03.2022
 * Time: 00:44
 */

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

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

        $this->content         =  new stdClass;


        if( $community = explode(",",  $this->config->community)){
            $i = 0;
            $arrcommunity = array();
            foreach ($community as $key => $value){
                $user = $DB->get_record('user', array('id' => $value));
                $userpicture = $OUTPUT->user_picture($user, array('size'=>28));
                $userurl = new moodle_url('/user/view.php', array('id' => $user->id));
                $userlink = html_writer::link($userurl, $userpicture .' '. fullname($user));
                $info = profile_user_record($user->id);

                $arrcommunity[$i]['userpicture'] = $userpicture;
                $arrcommunity[$i]['username'] = trim($user->firstname).' '.trim($user->lastname);
                $arrcommunity[$i]['userphone'] = $info->phone;
                $i++;
            }
        }

        if( $support = explode(",",  $this->config->support)){
            $i = 0;

            $arrsupport = array();
            foreach ($support as $key => $value){
                $user = $DB->get_record('user', array('id' => $value));
                $userpicture = $OUTPUT->user_picture($user, array('size'=>28));
                $userurl = new moodle_url('/user/view.php', array('id' => $user->id));
                $userlink = html_writer::link($userurl, $userpicture .' '. fullname($user));
                $info = profile_user_record($user->id);

                $arrsupport[$i]['userpicture'] = $userpicture;
                $arrsupport[$i]['username'] =  trim($user->firstname).' '.trim($user->lastname);
                $arrsupport[$i]['userphone'] = $info->phone;
                $i++;
            }
        }

        if( $paidprograms = explode(",",  $this->config->paidprograms)){
            $i = 0;
            $arrpaidprograms = array();
            foreach ($paidprograms as $key => $value){
                $user = $DB->get_record('user', array('id' => $value));
                $userpicture = $OUTPUT->user_picture($user, array('size'=>28));
                $userurl = new moodle_url('/user/view.php', array('id' => $user->id));
                $userlink = html_writer::link($userurl, $userpicture .' '. fullname($user));
                $info = profile_user_record($user->id);

                $arrpaidprograms[$i]['userpicture'] = $userpicture;
                $arrpaidprograms[$i]['username'] =  trim($user->firstname).' '.trim($user->lastname);
                $arrpaidprograms[$i]['userphone'] = $info->phone;
                $i++;
            }
        }


        $render = array();

//        $render['userpic'] =  $OUTPUT->user_picture($USER, array('size'=>28));

        $render['arrcommunity'] =  $arrcommunity;
        $render['arrsupport'] =  $arrsupport;
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