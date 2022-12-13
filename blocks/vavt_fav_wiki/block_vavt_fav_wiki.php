<?php
/**
 * User: densh
 * Date: 07.03.2022
 * Time: 00:44
 */

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

class block_vavt_fav_wiki extends block_base
{
    private $headerhidden = true;

    // включаем глобальную настройку из settings.php
    function has_config() {
        return true;
    }

    // отключить вывод имени блока
    //function hide_header() {
    //    return $this->headerhidden;
    //}

    public function init()
    {
        $this->title = get_string('pluginname', 'block_vavt_fav_wiki');
    }

    // получаем имя блока из настроек блока
    public function specialization() {
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_vavt_fav_wiki');
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

        $data = $DB->get_records_sql("SELECT * FROM mdl_vavt_favorite WHERE usermodified = {$USER->id} AND nameplugin = 'wiki'");

        if(!empty($data)){
            foreach ($data as $d){
                $usr = array();
                $name = $DB->get_record('course',  ['id'=>$d->objid]);

                if(!empty($name)){

                    $namecategory = $DB->get_field('course_categories', 'name', ['id'=>$name->category]);

                    $name = !empty($name->shortname) ? $name->shortname : $name->fullname;

                    if(strlen($name) >= 45){
                        $name = substr($name, 0, strrpos($name, ' '));
                        $name = $name . "...";
                    }

                    $name = $namecategory.'. '.$name;

                    $usr['pic'] =  self::get_course_image($d->objid);
                    if(empty($usr['userpic'])){
                        $usr['pic'] = new \moodle_url('/theme/boost_campus/pix/course_default_sm.png');
                    }
                    $usr['lnkevt'] = "/course/view.php?id=$d->objid";
                    $usr['name'] = $name;
                    $userinfo[] = $usr;
                }


            }

            $render['arrcommunity'] =  $userinfo;
            $render['hasdata'] =  true;
        }else{
            $render['hasdata'] =  false;
        }

        $this->content->text = $OUTPUT->render_from_template("block_vavt_fav_wiki/item", $render);


        return $this->content;
    }

    public function get_course_image($cid)
    {
        global $CFG;
        $url = '';
        require_once( $CFG->libdir . '/filelib.php' );

        $context = context_course::instance( $cid );
        $fs = get_file_storage();
        $files = $fs->get_area_files( $context->id, 'course', 'overviewfiles', 0 );

        foreach ( $files as $f )
        {
            if ( $f->is_valid_image() )
            {
                $url = moodle_url::make_pluginfile_url( $f->get_contextid(), $f->get_component(), $f->get_filearea(), null, $f->get_filepath(), $f->get_filename(), false );
            }
        }

        return $url;
    }
}