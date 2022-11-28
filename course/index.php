<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Lists the course categories
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package course
 */

require_once("../config.php");
require_once($CFG->dirroot. '/course/lib.php');

$categoryid = optional_param('categoryid', 0, PARAM_INT); // Category id
$site = get_site();

if ($CFG->forcelogin) {
    require_login();
}

$heading = $site->fullname;
if ($categoryid) {
    $category = core_course_category::get($categoryid); // This will validate access.
    $PAGE->set_category_by_id($categoryid);
    $PAGE->set_url(new moodle_url('/course/index.php', array('categoryid' => $categoryid)));
    $PAGE->set_pagetype('course-index-category');
    $heading = $category->get_formatted_name();
} else if ($category = core_course_category::user_top()) {
    // Check if there is only one top-level category, if so use that.
    $categoryid = $category->id;
    $PAGE->set_url('/course/index.php');
    if ($category->is_uservisible() && $categoryid) {
        $PAGE->set_category_by_id($categoryid);
        $PAGE->set_context($category->get_context());
        if (!core_course_category::is_simple_site()) {
            $PAGE->set_url(new moodle_url('/course/index.php', array('categoryid' => $categoryid)));
            $heading = $category->get_formatted_name();
        }
    } else {
        $PAGE->set_context(context_system::instance());
    }
    $PAGE->set_pagetype('course-index-category');
} else {
    throw new moodle_exception('cannotviewcategory');
}

$PAGE->set_pagelayout('coursecategory');
$courserenderer = $PAGE->get_renderer('core', 'course');
$PAGE->set_title($heading);
$PAGE->set_heading($heading);
//$content = $courserenderer->course_category($categoryid);

echo $OUTPUT->header();
echo $OUTPUT->skip_link_target();
//echo $content;

/** vavt start */

$parrentcategori = $DB->get_record('course_categories', ['id'=>$categoryid]);
$resulthtml = '';

if($child = $DB->get_records('course_categories', ['parent'=>$categoryid], 'sortorder')){
    //echo '<h1>'.$parrentcategori->name.'</h1>';
    foreach ($child as $c){
        $linksubcat = new moodle_url($PAGE->url, ['categoryid' => $c->id]);
        $namesubcat = "<h2>$c->name</h2><br>";
        $resulthtml .= \html_writer::link($linksubcat, $namesubcat);

        $resulthtml .= "<div class='grid-block'>";
        $resulthtml .= get_couses_category($c->id);
        $resulthtml .= '</div>';
    }
}else{
    //echo $namesubcat = "<h2>$parrentcategori->name</h2><br>";
    $resulthtml .= "<div class='grid-block'>";
    $resulthtml .= get_couses_category($parrentcategori->id);
    $resulthtml .= '</div>';
}

echo $OUTPUT->render_from_template("theme_boost_campus/courses_page", ['resulthtml'=>$resulthtml]);


function get_couses_category($id){
    global $DB, $USER;
    $data = $DB->get_records('course', ['category'=>$id], 'sortorder');
    $html ='';
    foreach ($data as $d){
        $html .= '<div class="grid-card"><div>';

        //$html .= '<i class="fa fa-vk fa-stack-1x fa-inverse"></i>';

        if($DB->record_exists('vavt_favorite', ['usermodified' => $USER->id,  'nameplugin' => 'wiki', 'objid'=>$d->id])){
            $has_addfav = 'addfav';
        }else{
            $has_addfav = '';
        }

        //$html .= "<span class='addfavoritevavt $has_addfav'' data-plugin='wiki' data-objid='$d->id' aria-label='Добавить в избранное' title='Добавить в избранное'></span>";

        $html .= "
        <span class='fa-stack fa-lg crklfvt'>
                    <span class='addfavoritevavt $has_addfav' data-plugin='wiki' data-objid='$d->id' aria-label='Добавить в избранное' title='Добавить в избранное'></span>
                </span>
        ";
        $imgcourse = get_course_image($d->id);
        if(empty($imgcourse)){
            $imgcourse = new \moodle_url('/theme/boost_campus/pix/course_default_sm.png');
        }
        $img =  "<img style='width: 100%;' src='$imgcourse'>";
        $namecourse = !empty($d->shortname) ? $d->shortname : $d->fullname;
        $url_btn = new \moodle_url('/course/view.php', ['id' => $d->id]);
        $html .= $img;
        $html .= \html_writer::link($url_btn, "<p>$namecourse</p>");

        $html .= '</div></div>';
    }
    return $html;
}

function get_course_image($cid)
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

/** vavt end */

// Trigger event, course category viewed.
$eventparams = array('context' => $PAGE->context, 'objectid' => $categoryid);
$event = \core\event\course_category_viewed::create($eventparams);
$event->trigger();

echo $OUTPUT->footer();
