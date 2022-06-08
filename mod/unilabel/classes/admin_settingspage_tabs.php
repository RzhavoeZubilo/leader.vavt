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

namespace mod_unilabel;

/**
 * Settings page providing a tabbed view.
 * @package     mod_unilabel
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_settingspage_tabs extends \admin_settingpage {

    /** @var array $tabs The tabs of this page */
    protected $tabs = array();
    /** @var string $description The description of this page */
    private $description = '';

    /**
     * Add a tab.
     *
     * @param admin_settingpage $tab A tab.
     * @return bool
     */
    public function add_tab(\admin_settingpage $tab) {
        foreach ($tab->settings as $setting) {
            $this->settings->{$setting->plugin.$setting->name} = $setting;
        }
        $this->tabs[] = $tab;
        return true;
    }

    /**
     * Add a setting page as new tab.
     *
     * @param \admin_settingpage $tab
     * @return bool
     */
    public function add($tab) {
        return $this->add_tab($tab);
    }

    /**
     * Set a description of this setting page.
     *
     * @param string $description
     * @return void
     */
    public function set_description($description) {
        $this->description = $description;
    }

    /**
     * Get tabs.
     *
     * @return array
     */
    public function get_tabs() {
        return $this->tabs;
    }

    /**
     * Generate the HTML output.
     *
     * @return string
     */
    public function output_html() {
        global $OUTPUT;

        $activetab = optional_param('activetab', '', PARAM_TEXT);
        $context = array('tabs' => array());
        $havesetactive = false;

        foreach ($this->get_tabs() as $tab) {
            $active = false;

            // Default to first tab it not told otherwise.
            if (empty($activetab) && !$havesetactive) {
                $active = true;
                $havesetactive = true;
            } else if ($activetab === $tab->name) {
                $active = true;
            }

            $context['tabs'][] = array(
                'name' => $tab->name,
                'displayname' => $tab->visiblename,
                'html' => $tab->output_html(),
                'active' => $active,
            );
        }

        if (!empty($context['tabs'])) {
            $context['hastabs'] = true;
        }
        $context['description'] = $this->description;

        return $OUTPUT->render_from_template('mod_unilabel/admin_setting_tabs', $context);
    }

}

