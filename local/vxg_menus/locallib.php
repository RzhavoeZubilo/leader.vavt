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
 * Functions for local_vxg_menus
 *
 * @package    local_vxg_menus
 * @copyright  Veloxnet
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Returns the keys of a navhation_node
 *
 * @param navigation_node $navigationnode
 * @return array $allchildren
 */
function local_vxg_menus_get_all_childrenkeys(navigation_node $navigationnode) {
    // Empty array to hold all children.
    $allchildren = array();

    // No, this node does not have children anymore.
    if (count($navigationnode->children) == 0) {
        return array();

        // Yes, this node has children.
    } else {
        // Get own own children keys.
        $childrennodeskeys = $navigationnode->get_children_key_list();
        // Get all children keys of our children recursively.
        foreach ($childrennodeskeys as $ck) {
            $allchildren = array_merge($allchildren, local_vxg_menus_get_all_childrenkeys($navigationnode->get($ck)));
        }
        // And add our own children keys to the result.
        $allchildren = array_merge($allchildren, $childrennodeskeys);

        // Return everything.
        return $allchildren;
    }
}

/**
 * Returns the roles that can be assigned
 *
 * @return array $rolenames
 */
function local_vxg_menus_get_assignable_roles() {
    global $DB;

    $roleids = $DB->get_fieldset_select('role_context_levels', 'roleid',
        'contextlevel = ? OR contextlevel = ? OR contextlevel = ?', array('10', '40', '50'));

    $insql = 'IN (' . implode(',', $roleids) . ')';

    $sql = 'SELECT shortname FROM {role} WHERE id ' . $insql . ' ORDER BY id';

    $rolenames = $DB->get_fieldset_sql($sql);

    $rolenames = array_combine(array_values($rolenames), array_values($rolenames));

    return $rolenames;

}

/**
 * Returns the current user's roles name
 *
 * @return array $rolenames
 */
function local_vxg_menus_get_user_role_names() {
    global $USER, $COURSE;

    $userroles = get_user_roles(context_course::instance($COURSE->id), $USER->id);

    $rolenames = array();
    foreach ($userroles as $role) {
        $rolenames[] = $role->shortname;
    }

    return $rolenames;

}

/**
 * Returns the current user's roles
 *
 * @return array $roleids
 */
function local_vxg_menus_get_user_role_ids() {
    global $USER, $COURSE;

    $userroles = get_user_roles(context_course::instance($COURSE->id), $USER->id);

    $roleids = array();
    foreach ($userroles as $role) {
        $roleids[] = $role->roleid;
    }

    return $roleids;

}

/**
 * Adds navigation nodes to the global_navigation
 *
 * @param global_navigation $nav
 * @return void
 */
function local_vxg_menus_add_new_navigation_nodes(global_navigation $nav) {
    global $DB, $PAGE, $COURSE;

    $userroles = local_vxg_menus_get_user_role_ids();

    $nodesdata = $DB->get_records('local_vxg_menus');

    foreach ($nodesdata as $nodedata) {
        if ($nav) {
            // If disabled not show.
            if ($nodedata->disabled || (!empty($nodedata->lang) && current_language() != $nodedata->lang)) {
                continue;
            }

            // Check is user has a role for this menu.
            $userhasrole = false;
            if ($noderoles = $DB->get_records('local_vxg_menus_right',
                array('objecttype' => 'menu', 'objectid' => $nodedata->id))) {
                foreach ($noderoles as $noderole) {
                    if (in_array($noderole->roleid, $userroles)) {
                        $userhasrole = true;
                        continue;
                    }
                }
            } else {
                $userhasrole = true;
            }

            $iconarr = explode('/', $nodedata->icon, 2);

            $courseid = $COURSE->id;

            if (!empty($nodedata->params && $courseid == SITEID)) {
                continue;
            }

            $paramarray = array();
            if (!empty($nodedata->params) && $courseid != SITEID) {
                $paramarray = array($nodedata->params => $courseid);
            }

            // Create node.
            if ($userhasrole || is_siteadmin()) {
                $id    = $nodedata->id;
                $name  = $nodedata->name;
                $url   = new moodle_url('/' . $nodedata->url, $paramarray);
                $order = $nodedata->menu_order;

                if (isset($nodedata->icon) && !empty($nodedata->icon)) {
                    $icon = new pix_icon($iconarr[1], $name, $iconarr[0]);
                } else {
                    $icon = new pix_icon('t/edit_menu', $name);
                }

                $newnode = navigation_node::create(
                    $name,
                    $url,
                    navigation_node::NODETYPE_LEAF,
                    $name,
                    'vxg_' . $id,
                    $icon
                );

                // Make visible in flatnav.
                $newnode->showinflatnavigation = true;

                if ($PAGE->url->compare($url, URL_MATCH_BASE)) {
                    $newnode->make_active();
                }

                if ($order == 1) {
                    // Get the first node to add before that.
                    $firstnode = $nav->get_children_key_list()[0];
                    $nav->add_node($newnode, $firstnode);
                } else {
                    $nav->add_node($newnode);
                }
            }
        }

    }
}

/**
 * Returns the role id from the role name
 *
 * @param string $shortname
 * @return integer $id
 */
function local_vxg_menus_get_role_id($shortname) {
    global $DB;
    $role = $DB->get_record('role', array('shortname' => $shortname));
    return $role->id;
}

/**
 * Returns the role name from the role id
 *
 * @param integer $id
 * @return integer $shortname
 */
function local_vxg_menus_get_role_shortname($id) {
    global $DB;
    $role = $DB->get_record('role', array('id' => $id));
    return $role->shortname;
}
