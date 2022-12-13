<?php
/**
 * User: densh
 * Date: 07.03.2022
 * Time: 00:44
 */

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

class block_vavt_fav_alumni extends block_base
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
        $this->title = get_string('pluginname', 'block_vavt_fav_alumni');
    }

    // получаем имя блока из настроек блока
    public function specialization() {
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_vavt_fav_alumni');
        } else {
            $this->title = $this->config->title;
        }
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.

    public function get_content() {
        global $DB, $OUTPUT,$CFG, $USER;

        $this->content         =  new stdClass;

        $render = array();
        $userinfo = array();

        $data = $DB->get_records_sql("SELECT * FROM mdl_vavt_favorite WHERE usermodified = {$USER->id} AND nameplugin = 'alumni'");

        if(!empty($data)){
            foreach ($data as $d){
                $usr = array();
                $user = \core_user::get_user($d->objid);
                //$usr['userpic'] =  $OUTPUT->user_picture($user, array('size'=>28));

                $size = array('large' => 'f1', 'small' => 'f2');
                $src = false;
                if ($user->picture) {
                    $urlpic = new moodle_url('/user/pix.php/'.$user->id.'/f1.jpg');
                    $usr['userpic'] =  "<a href='/user/profile.php?id=$user->id'><img src='$urlpic'></a>";
                }else{
                    $usr['userpic'] =  $OUTPUT->user_picture($user, array('size'=>100));
                }

                $usr['name'] = "<a href='/user/profile.php?id=$user->id'>".fullname($user)."</a>";
                $userinfo[] = $usr;
            }

            $render['arrcommunity'] =  $userinfo;
            $render['hasdata'] =  true;
        }else{
            $render['hasdata'] =  false;
        }

        $this->content->text = $OUTPUT->render_from_template("block_vavt_fav_alumni/item", $render);


        return $this->content;
    }
}