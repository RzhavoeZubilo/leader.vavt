<?php

// Данные настройки будут применены к конкретному экземпляру блока/плагина
// и доступны в режиме редактирования страницы

// Доступ к ним можно получить внутри класса
// $this->config->config_title

class block_vavt_news_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_vavt_news'));
        $mform->setDefault('config_title', 'default value');
        $mform->setType('config_title', PARAM_TEXT);

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_text', get_string('blockstring', 'block_vavt_news'));
        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_RAW);

    }
}