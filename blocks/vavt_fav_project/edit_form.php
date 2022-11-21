<?php
/**
 * User: densh
 * Date: 10.03.2022
 * Time: 02:21
 */

class block_vavt_fav_project_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        $mform->addElement('html', '<b>Введите ID пользователей через запятую</b><br>');

        $mform->addElement('text', 'config_community', 'Сообщество');
        $mform->setDefault('config_community', 'default value');
        $mform->setType('config_community', PARAM_RAW);

    }
}