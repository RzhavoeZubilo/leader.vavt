<?php
/**
 * User: densh
 * Date: 10.03.2022
 * Time: 02:21
 */

class block_vavt_fav_event_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_vavt_news'));
        $mform->setDefault('config_title', 'default value');
        $mform->setType('config_title', PARAM_TEXT);

    }
}