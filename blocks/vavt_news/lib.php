<?php
/**
 * User: densh
 * Date: 09.03.2022
 * Time: 21:20
 */




/**
 * Form for editing vavt_news block instances.
 *
 * @copyright 2010 Petr Skoda (http://skodak.org)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package   block_vavt_news
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
//function block_vavt_news_pluginfile($course, $birecord_or_cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
//    global $DB, $CFG, $USER;
//
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
//    if ($filearea !== 'content') {
//        send_file_not_found();
//    }
//
//    $fs = get_file_storage();
//
//    $filename = array_pop($args);
//    $filepath = $args ? '/'.implode('/', $args).'/' : '/';
//
//    if (!$file = $fs->get_file($context->id, 'block_vavt_news', 'content', 0, $filepath, $filename) or $file->is_directory()) {
//        send_file_not_found();
//    }
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
//}
function block_vavt_news_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    // Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    // Make sure the filearea is one of those used by the plugin.
    if ($filearea !== 'expectedfilearea' && $filearea !== 'anotherexpectedfilearea') {
        return false;
    }

    // Make sure the user is logged in and has access to the module (plugins that are not course modules should leave out the 'cm' part).
    require_login($course, true, $cm);

    // Check the relevant capabilities - these may vary depending on the filearea being accessed.
    if (!has_capability('mod/MYPLUGIN:view', $context)) {
        return false;
    }

    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    $itemid = array_shift($args); // The first item in the $args array.

    // Use the itemid to retrieve any relevant data records and perform any security checks to see if the
    // user really does have access to the file in question.

    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/'; // $args is empty => the path is '/'
    } else {
        $filepath = '/'.implode('/', $args).'/'; // $args contains elements of the filepath
    }

    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'mod_MYPLUGIN', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
    }

    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering.
    send_stored_file($file, 86400, 0, $forcedownload, $options);
}

/**
 * Given an array with a file path, it returns the itemid and the filepath for the defined filearea.
 *
 * @param  string $filearea The filearea.
 * @param  array  $args The path (the part after the filearea and before the filename).
 * @return array The itemid and the filepath inside the $args path, for the defined filearea.
 */
function block_vavt_news_get_path_from_pluginfile(string $filearea, array $args) : array {
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
function block_vavt_news_global_db_replace($search, $replace) {
    global $DB;

    $instances = $DB->get_recordset('block_instances', array('blockname' => 'vavt_news'));
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