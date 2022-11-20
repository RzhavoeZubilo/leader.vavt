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
 * This file contains the multiselect profile field class.
 *
 * @copyright 2014 Nitin Jain
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

/**
 * Define multiselect field.
 *
 * @copyright 2014 Nitin Jain
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class profile_define_multiselect extends profile_define_base
{
    /**
     * Define the setting for a multi select custom field.
     *
     * @param moodleform $form The user form
     */
    public function define_form_specific($form)
    {
        /// Param 1 for menu type contains the options
        $form->addElement('textarea', 'param1', get_string('profilemenuoptions', 'admin'), array('rows' => 6, 'cols' => 40));
        $form->setType('param1', PARAM_TEXT);

        /// Default data
        $form->addElement('text', 'defaultdata', get_string('profiledefaultdata', 'admin'), 'size="50"');
        $form->setType('defaultdata', PARAM_TEXT);
    }

    /**
     * Validate the data from the profile field form.
     *
     * @param stdClass $data  From the add/edit profile field form
     * @param array    $files
     *
     * @return array associative array of error messages
     */
    public function define_validate_specific($data, $files)
    {
        $err = array();

        $data->param1 = str_replace("\r", '', $data->param1);

        /// Check that we have at least 2 options
        if (($options = explode("\n", $data->param1)) === false) {
            $err['param1'] = get_string('profilemenunooptions', 'admin');
        } elseif (count($options) < 2) {
            $err['param1'] = get_string('profilemenutoofewoptions', 'admin');

        /// Check the default data exists in the options
        } elseif (!empty($data->defaultdata) and !in_array($data->defaultdata, $options)) {
            $err['defaultdata'] = get_string('profilemenudefaultnotinoptions', 'admin');
        }

        return $err;
    }

    /**
     * Preprocess data from the profile field form before
     * it is saved.
     *
     * @param stdClass $data from the add/edit profile field form
     *
     * @return stdClass processed data object
     */
    public function define_save_preprocess($data)
    {
        $data->param1 = str_replace("\r", '', $data->param1);

        return $data;
    }
}
