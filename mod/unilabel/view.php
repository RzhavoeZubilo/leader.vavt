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
 * unilabel module
 *
 * @package     mod_unilabel
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$id = optional_param('id', 0, PARAM_INT);    // Course Module ID.
$l = optional_param('l', 0, PARAM_INT);     // The unilabel ID.

if ($id) {
    $PAGE->set_url('/mod/unilabel/index.php', ['id' => $id]);
    if (!$cm = get_coursemodule_from_id('unilabel', $id)) {
        throw new \moodle_exception('invalidcoursemodule');
    }

    if (!$course = $DB->get_record('course', ['id' => $cm->course])) {
        throw new \moodle_exception('coursemisconf');
    }

    if (!$unilabel = $DB->get_record('unilabel', ['id' => $cm->instance])) {
        throw new \moodle_exception('invalidcoursemodule');
    }
} else {
    $PAGE->set_url('/mod/unilabel/index.php', ['l' => $l]);
    if (!$unilabel = $DB->get_record('unilabel', ['id' => $l])) {
        throw new \moodle_exception('invalidcoursemodule');
    }
    if (!$course = $DB->get_record('course', ['id' => $unilabel->course])) {
        throw new \moodle_exception('coursemisconf');
    }
    if (!$cm = get_coursemodule_from_instance('unilabel', $unilabel->id, $course->id)) {
        throw new \moodle_exception('invalidcoursemodule');
    }
}

require_login($course, true, $cm);

redirect("$CFG->wwwroot/course/view.php?id=$course->id");
