<?php
/**
 * User: densh
 * Date: 17.05.2022
 * Time: 00:32
 */
global $DB, $OUTPUT, $CFG;

require_once('../../config.php');
require_once($CFG->dirroot.'/calendar/lib.php');

//require_once('../../blocks/moodleblock.class.php');

//use block_calendar_upcoming_custom\calendar_upcoming_custom;

$eventnum = optional_param('event', '0', PARAM_INT);
$allevent = optional_param('allevent', '', PARAM_TEXT);

$allevent = json_decode($allevent);
//print_object($allevent);
//$evnt = $allevent[$eventnum];
//print_object($evnt);

//$contentitemservice = \block_calendar_upcoming_custom\ajax::run($eventnum);
//return $contentitemservice;

//print_object($contentitemservice);


$event = $allevent[$eventnum];

$checkevent = array_key_exists($eventnum, $allevent);

$starttime = $event->timestart;
$endtime = $event->timestart + $event->timeduration;
// строка формата Пятница 18 марта,
$daystart = calendar_day_representation($event->timestart, time(), true) . ', ';
$dayend = calendar_day_representation($event->timestart + $event->timeduration, time(), true) . ', ';
// время начала
$timestart = calendar_time_representation($event->timestart);
$timeend = calendar_time_representation($event->timestart + $event->timeduration);

$arrdaystart = explode(' ', substr(trim($daystart),0,-1));

if (empty($linkparams) || !is_array($linkparams)) {
    $linkparams = array();
}
$linkparams['view'] = 'day';


$url = calendar_get_link_href(new \moodle_url(CALENDAR_URL . 'view.php', $linkparams), 0, 0, 0, $starttime);
$eventday = \html_writer::link($url, $arrdaystart[1].' '.$arrdaystart[2]);

$timestr = $timestart;
if(!empty($timeend)) $timestr .=  ' - ' . $timeend;

$daystart = 0;
$render = [
    'name'=>$event->name,
    'eventdayofweek'=>$arrdaystart[0],
    'eventday'=>$eventday,
    'timestr'=>$timestr

];

$html = $OUTPUT->render_from_template("block_calendar_upcoming_custom/upcoming_block", $render);

echo $html;

//require_once('./block_calendar_upcoming_custom.php');
//
//$contentitemservice = new block_calendar_upcoming_custom;
//$res = $contentitemservice->get_content($eventnum);
//return $res;