<?php

/**
 * Form for editing vavt_event block instances.
 *
 * @copyright 2010 Petr Skoda (http://skodak.org)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package   block_vavt_event
 * @category  files
 * @param stdClass $course course object
 * @param stdClass $birecord_or_cm block instance record
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool
 * @todo MDL-36050 improve capability check on stick blocks, so we can check user capability before sending images.
 */
function block_vavt_event_pluginfile($course, $birecord_or_cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $DB, $CFG, $USER;

//    if ($context->contextlevel != CONTEXT_BLOCK) {
//        send_file_not_found();
//    }
//
//    // If block is in course context, then check if user has capability to access course.
//    if ($context->get_course_context(false)) {
//        require_course_login($course);
//    } else if ($CFG->forcelogin) {
//        require_login();
//    } else {
//        // Get parent context and see if user have proper permission.
//        $parentcontext = $context->get_parent_context();
//        if ($parentcontext->contextlevel === CONTEXT_COURSECAT) {
//            // Check if category is visible and user can view this category.
//            if (!core_course_category::get($parentcontext->instanceid, IGNORE_MISSING)) {
//                send_file_not_found();
//            }
//        } else if ($parentcontext->contextlevel === CONTEXT_USER && $parentcontext->instanceid != $USER->id) {
//            // The block is in the context of a user, it is only visible to the user who it belongs to.
//            send_file_not_found();
//        }
//        // At this point there is no way to check SYSTEM context, so ignoring it.
//    }
//
//
//    $itemid = array_shift($args); // The first item in the $args array.
//
//    $fs = get_file_storage();
//
//    $filename = array_pop($args);
//    $filepath = $args ? '/'.implode('/', $args).'/' : '/';
//
//    $file = $fs->get_file($context->id, 'block_vavt_event', $filearea, $itemid, $filepath, $filename);
//
////    if (!$file = $fs->get_file($context->id, 'block_vavt_event', 'content', $itemid, $filepath, $filename) or $file->is_directory()) {
////        send_file_not_found();
////    }
//
//    if ($parentcontext = context::instance_by_id($birecord_or_cm->parentcontextid, IGNORE_MISSING)) {
//        if ($parentcontext->contextlevel == CONTEXT_USER) {
//            // force download on all personal pages including /my/
//            //because we do not have reliable way to find out from where this is used
//            $forcedownload = true;
//        }
//    } else {
//        // weird, there should be parent context, better force dowload then
//        $forcedownload = true;
//    }
//
//    // NOTE: it woudl be nice to have file revisions here, for now rely on standard file lifetime,
//    //       do not lower it because the files are dispalyed very often.
//    \core\session\manager::write_close();
//    send_stored_file($file, null, 0, $forcedownload, $options);



    $itemid = array_shift($args); // The first item in the $args array.

    $fs = get_file_storage();

    $filename = array_pop($args);
    $filepath = $args ? '/'.implode('/', $args).'/' : '/';

    $file = $fs->get_file($context->id, 'block_vavt_event', $filearea, $itemid, $filepath, $filename);

    $forcedownload = false;

    \core\session\manager::write_close();
    send_stored_file($file, null, 0, $forcedownload, $options);
}


/**
 * Given an array with a file path, it returns the itemid and the filepath for the defined filearea.
 *
 * @param  string $filearea The filearea.
 * @param  array  $args The path (the part after the filearea and before the filename).
 * @return array The itemid and the filepath inside the $args path, for the defined filearea.
 */
function block_vavt_event_get_path_from_pluginfile(string $filearea, array $args) : array {
    // This block never has an itemid (the number represents the revision but it's not stored in database).
    array_shift($args);

    // Get the filepath.
    if (empty($args)) {
        $filepath = '/';
    } else {
        $filepath = '/' . implode('/', $args) . '/';
    }

    return [
        'itemid' => 0,
        'filepath' => $filepath,
    ];
}

/**
 * Perform global search replace such as when migrating site to new URL.
 * @param  $search
 * @param  $replace
 * @return void
 */
function block_vavt_event_global_db_replace($search, $replace) {
    global $DB;

    $instances = $DB->get_recordset('block_instances', array('blockname' => 'vavt_event'));
    foreach ($instances as $instance) {
        // TODO: intentionally hardcoded until MDL-26800 is fixed
        $config = unserialize_object(base64_decode($instance->configdata));
        if (isset($config->text) and is_string($config->text)) {
            $config->text = str_replace($search, $replace, $config->text);
            $DB->update_record('block_instances', ['id' => $instance->id,
                'configdata' => base64_encode(serialize($config)), 'timemodified' => time()]);
        }
    }
    $instances->close();
}