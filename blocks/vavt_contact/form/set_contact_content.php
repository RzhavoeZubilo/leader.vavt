<?php

global $CFG;

require_once($CFG->libdir . '/formslib.php');

class set_contact_content extends \moodleform {
    const EDITOR_OPTIONS = [
        'changeformat' => 1,
        'noclean' => 0,
        'trusttext' => 0,

        'subdirs' => 0,
        'maxfiles' => EDITOR_UNLIMITED_FILES,
        'accepted_types' => ['.jpg', '.jpeg', '.png']
    ];

    function definition() {
        global $DB, $USER;

        $editoroptions = self::EDITOR_OPTIONS;

        $mform = &$this->_form;

        $mform->addElement('header', 'headertype', 'Содержимое страницы "Контакты"');

        $mform->addElement('html', '<hr>');
        $mform->addElement('editor', 'content_page', 'Содержимое');


        $this->add_action_buttons();
    }

    function validation($data, $files) {
        $errors = parent::validation($data, $files); // TODO: Change the autogenerated stub
        return $errors;
    }

}
