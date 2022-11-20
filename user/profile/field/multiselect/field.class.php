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
 * Handles displaying and editing the multiselect field.
 *
 * @copyright 2014 Nitin Jain
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class profile_field_multiselect extends profile_field_base
{
    public $options;
    public $datakey;

    /**
     * Constructor method.
     * Pulls out the options for the menu from the database and sets the
     * the corresponding key for the data if it exists.
     */
    public function __construct($fieldid = 0, $userid = 0, $fielddata = null)
    {
        //first call parent constructor
        parent::__construct($fieldid, $userid, $fielddata);
        /// Param 1 for menu type is the options

        $options = explode("\n", $this->field->param1);

        $this->options = array();
        if ($this->field->required) {
            $this->options[''] = get_string('choose').'...';
        }
        foreach ($options as $key => $option) {
            $this->options[$key] = format_string($option); //multilang formatting
        }

        /// Set the data key
        if ($this->data !== null) {
            $this->data = str_replace("\r", '', $this->data);
            $this->datatmp = explode("\n", $this->data);
            foreach ($this->datatmp as $key => $option1) {
                $this->datakey[] = (int) array_search($option1, $this->options);
            }
        }
    }

    /**
     * Create the code snippet for this field instance
     * Overwrites the base class method.
     *
     * @param   object   moodleform instance
     */
    public function edit_field_add($mform)
    {
        $mform->addElement('select', $this->inputname, format_string($this->field->name), $this->options);
        $mform->getElement($this->inputname)->setMultiple(true);
    }

    /**
     * Set the default value for this field instance
     * Overwrites the base class method.
     */
    public function edit_field_set_default($mform)
    {
        $defaultkey = '';
        if (false !== array_search($this->field->defaultdata, $this->options)) {
            $defaultkey = (int) array_search($this->field->defaultdata, $this->options);
        }

        $mform->setDefault($this->inputname, $defaultkey);
    }

    /**
     * The data from the form returns the key. This should be converted to the
     * respective option string to be saved in database
     * Overwrites base class accessor method.
     *
     * @param mixed    $data       - the key returned from the select input in the form
     * @param stdClass $datarecord The object that will be used to save the record
     */
    public function edit_save_data_preprocess($data, $datarecord)
    {
        //print "<pre>";print_r($data);die;
        $string = '';
        if (is_array($data)) {
            foreach ($data as $key) {
                if (isset($this->options[$key])) {
                    $string .= $this->options[$key]."\r\n";
                }
            }

            return substr($string, 0, -2);
        }

        return isset($this->options[$data]) ? $this->options[$data] : null;
    }

    /**
     * When passing the user object to the form class for the edit profile page
     * we should load the key for the saved data
     * Overwrites the base class method.
     *
     * @param   object   user object
     */
    public function edit_load_user_data($user)
    {
        $user->{$this->inputname} = $this->datakey;
    }

    /**
     * HardFreeze the field if locked.
     *
     * @param   object   instance of the moodleform class
     */
    public function edit_field_set_locked($mform)
    {
        if (!$mform->elementExists($this->inputname)) {
            return;
        }
        if ($this->is_locked() and !has_capability('moodle/user:update', context_system::instance())) {
            $mform->hardFreeze($this->inputname);
            $mform->setConstant($this->inputname, $this->datakey);
        }
    }

    /**
     * Convert external data (csv file) from value to key for processing later
     * by edit_save_data_preprocess.
     *
     * @param string $value one of the values in menu options
     *
     * @return int options key for the menu
     */
    public function convert_external_data($value)
    {
        $retval = array_search($value, $this->options);

        // If value is not found in options then return null, so that it can be handled
        // later by edit_save_data_preprocess
        if ($retval === false) {
            $retval = null;
        }

        return $retval;
    }
}
