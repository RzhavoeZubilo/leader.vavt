<?php
/**
 * User: densh
 * Date: 10.03.2022
 * Time: 02:21
 */


// ФОРМА которая появляется при вызове настроек блока через шестеренку

class block_vavt_contact_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        // Section header title according to language file.
//        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // A sample string variable with a default value.

        $mform->addElement('html', '<b>Введите ID пользователей через запятую</b><br>');

        $mform->addElement('text', 'config_community', 'Сообщество');
        $mform->setDefault('config_community', '');
        $mform->setType('config_community', PARAM_RAW);

        $mform->addElement('text', 'config_support', 'Служба ТП');
        $mform->setDefault('config_support', '');
        $mform->setType('config_support', PARAM_RAW);

        $mform->addElement('text', 'config_paidprograms', 'Платные программы');
        $mform->setDefault('config_paidprograms', '');
        $mform->setType('config_paidprograms', PARAM_RAW);

    }
}