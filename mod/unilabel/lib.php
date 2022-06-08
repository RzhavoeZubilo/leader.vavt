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

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param \stdClass $unilabel
 * @return bool|int
 */
function unilabel_add_instance($unilabel) {
    global $DB;

    $unilabel->timemodified = time();

    $unilabel->id = $DB->insert_record('unilabel', $unilabel);
    $DB->update_record('unilabel', $unilabel);

    $completiontimeexpected = !empty($unilabel->completionexpected) ? $unilabel->completionexpected : null;
    \core_completion\api::update_completion_date_event($unilabel->coursemodule, 'unilabel', $unilabel->id, $completiontimeexpected);

    return $unilabel->id;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param \stdClass $unilabel
 * @return bool
 */
function unilabel_update_instance($unilabel) {
    global $DB;

    $unilabel->timemodified = time();
    $unilabel->id = $unilabel->instance;

    $completiontimeexpected = !empty($unilabel->completionexpected) ? $unilabel->completionexpected : null;
    \core_completion\api::update_completion_date_event($unilabel->coursemodule, 'unilabel', $unilabel->id, $completiontimeexpected);

    return $DB->update_record('unilabel', $unilabel);
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id
 * @return bool
 */
function unilabel_delete_instance($id) {
    global $DB;

    if (!$unilabel = $DB->get_record('unilabel', ['id' => $id])) {
        return false;
    }

    $result = true;

    $cm = get_coursemodule_from_instance('unilabel', $id);
    \core_completion\api::update_completion_date_event($cm->id, 'unilabel', $unilabel->id, null);

    // Delete content form plugins.
    \mod_unilabel\factory::delete_plugin_content($unilabel->id);

    if (!$DB->delete_records('unilabel', ['id' => $unilabel->id])) {
        $result = false;
    }

    return $result;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 *
 * @param \stdClass $coursemodule
 * @return cached_cm_info|null
 */
function unilabel_get_coursemodule_info($coursemodule) {
    global $DB;

    if ($unilabel = $DB->get_record('unilabel', ['id' => $coursemodule->instance], 'id, name, intro, introformat, unilabeltype')) {
        $content = format_module_intro('unilabel', $unilabel, $coursemodule->id, false);

        $info = new cached_cm_info();
        // No filtering here because this info is cached and filtered later.
        $info->content = $content;
        $info->name = $unilabel->name;
        return $info;
    } else {
        return null;
    }
}

/**
 * Set the content for the course page to show there.
 *
 * @param \cm_info $cm
 * @return void
 */
function unilabel_cm_info_view(\cm_info $cm) {
    global $DB, $PAGE;

    $renderer = $PAGE->get_renderer('mod_unilabel');
    $unilabel = $DB->get_record('unilabel', ['id' => $cm->instance], 'id, course, name, intro, introformat, unilabeltype');
    $unilabeltype = \mod_unilabel\factory::get_plugin($unilabel->unilabeltype);
    if (!$unilabeltype->is_active()) {
        $unilabeltype = \mod_unilabel\factory::get_plugin('simpletext');
    }

    $content = ['content' => $unilabeltype->get_content($unilabel, $cm, $renderer)];

    // Add the edit link if needed.
    if ($PAGE->user_is_editing()) {
        if (has_capability('mod/unilabel:edit', $cm->context)) {
            $editlink = new \stdClass();
            $editlink->title = get_string('editcontent', 'mod_unilabel');
            $editlink->url = new \moodle_url('/mod/unilabel/edit_content.php', ['cmid' => $cm->id]);
            $content['editlink'] = $editlink;
        }
    }

    $cm->set_content($renderer->render_from_template('mod_unilabel/content', $content));
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 *
 * @param \stdClass $data the data submitted from the reset course.
 * @return array status array
 */
function unilabel_reset_userdata($data) {

    // Any changes to the list of dates that needs to be rolled should be same during course restore and course reset.
    // See MDL-9367.

    return [];
}

/**
 * Returns all other caps used in module
 *
 * @return array
 */
function unilabel_get_extra_capabilities() {
    return ['moodle/site:accessallgroups'];
}

/**
 * What features are supported in this activity?
 * @uses FEATURE_IDNUMBER
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool|null True if module supports feature, false if not, null if doesn't know
 */
function unilabel_supports($feature) {
    switch ($feature) {
        case FEATURE_IDNUMBER:
            return false;
        case FEATURE_GROUPS:
            return false;
        case FEATURE_GROUPINGS:
            return false;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return false;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_NO_VIEW_LINK:
            return true;

        default:
            return null;
    }
}

/**
 * Check if the module has any update that affects the current user since a given time.
 *
 * @param  cm_info $cm course module data
 * @param  int $from the time to check updates from
 * @param  array $filter  if we need to check only specific updates
 * @return stdClass an object with the different type of areas indicating if they were updated or not
 * @since Moodle 3.2
 */
function unilabel_check_updates_since(cm_info $cm, $from, $filter = []) {
    $updates = course_check_module_updates_since($cm, $from, [], $filter);
    return $updates;
}
