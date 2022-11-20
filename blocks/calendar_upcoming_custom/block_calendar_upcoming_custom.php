<?php

defined('MOODLE_INTERNAL') || die();

class block_calendar_upcoming_custom extends block_base {

    /**
     * Initialise the block.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_calendar_upcoming_custom');
    }

    public function kurva() {
        return 'kurva';
    }

    /**
     * Return the content of this block.
     */
    public function get_content($eventnum = 0) {
        global $CFG, $OUTPUT, $PAGE;

        $PAGE->requires->js_call_amd('block_calendar_upcoming_custom/script', 'init');

        require_once($CFG->dirroot.'/calendar/lib.php');

        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        $courseid = $this->page->course->id;
        $categoryid = ($this->page->context->contextlevel === CONTEXT_COURSECAT) ? $this->page->category->id : null;
        $calendar = \calendar_information::create(time(), $courseid, $categoryid);
        list($data, $template) = calendar_get_view($calendar, 'upcoming_mini', true);

        $nextcontent = array();
        $nextcontent = $data->events;

        $event = $nextcontent[0];

        $starttime = $event->timestart;
        $endtime = $event->timestart + $event->timeduration;
        // строка формата Пятница 18 марта,
        $daystart = calendar_day_representation($event->timestart, time(), true) . ', ';
        $dayend = calendar_day_representation($event->timestart + $event->timeduration, time(), true) . ', ';
        // время начала
        $timestart = calendar_time_representation($event->timestart);
        $timeend = calendar_time_representation($event->timestart + $event->timeduration);

        // пошли костыли  =)
        if(empty($data->events)){
            $this->content->text = '<h5>Нет предстоящих событий</h5>';
            return $this->content;
        }

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
            'timestr'=>$timestr,
            'allevent'=>json_encode($data->events),

        ];

        $this->content->text = $OUTPUT->render_from_template("block_calendar_upcoming_custom/upcoming_block", $render);

        $linkparams['view'] = 'month';
        $url = calendar_get_link_href(new \moodle_url(CALENDAR_URL . 'view.php', $linkparams), 0, 0, 0, $starttime);

        $footernav = '<input type="hidden" id="numevent" value="0">';
        $footernav .= '<i class="fa fa-caret-left fa-2x prevcal prevdis" id="cal-prev" aria-hidden="true" style="padding-right: 10px;"></i>';
        $footernav .= '<i class="fa fa-caret-right fa-2x prevcal" id="cal-next" aria-hidden="true" style="padding-left: 10px;padding-right: 10px;"></i>';
        $footernav .= html_writer::link($url, get_string('gotocalendar', 'block_calendar_upcoming_custom'));
        $this->content->footer = html_writer::div($footernav, 'gotocal');

        return $this->content;
    }


}
