<?php

global $CFG;

require_once($CFG->libdir . '/formslib.php');


class set_menu extends \moodleform
{
    const EDITOR_OPTIONS = [
        'changeformat' => 1,
        'noclean'      => 0,
        'trusttext'    => 0,

        'subdirs' => 0,
        'maxfiles' => EDITOR_UNLIMITED_FILES,
        'accepted_types' => ['.jpg', '.jpeg', '.png']
    ];

    function definition()
    {
        global $DB, $USER;

        $editoroptions = self::EDITOR_OPTIONS;

        $mform = &$this->_form;

        $mform->addElement('header', 'headertype', 'Разделы меню');
        $mform->addElement('html', '<h4>Примеры и рекомендации: </h4>');
        $mform->addElement('html', '<p>
- через запятую укажите 3 параметра (имя, ссылка, иконка);<br>
- если это заголовок меню, в качестве ссылки используйте символ #;<br>
- если это подзаголовок, он должен начинаться с дефиса - ;<br>
- в качестве иконок используется шрифт <a href="https://fontawesome.com/v4/icons/">FontAwesome</a><br>

</p>
<p>
<b>Пример меню (изначальный вариант):</b><br>
СООБЩЕСТВО, #, fa-group<br>
-Про сообщество, /blocks/vavt_contact/community.php, fa-group<br>
-Новости, /blocks/vavt_news/, fa-newspaper-o<br>
-Выпускники, /local/alumni/, fa-sitemap<br>
-Эксперты, /local/tilda/index.php, fa-user<br>
ОБУЧЕНИЕ, #, fa-graduation-cap<br>
-Программа ЛидерыПРО, /course/index.php?categoryid=2<br>
-Дополнительные курсы, /course/index.php?categoryid=6<br>
-Мастерклассы, /course/index.php?categoryid=7<br>
-Лучшие практики, /course/index.php?categoryid=8м
-Платные программы, /course/index.php?categoryid=11<br>
База знаний, /mod/data/view.php?id=4, fa-book<br>
Лучшие практики, /local/faqwiki/, fa-book<br>
ВИТРИНА ПРЕДПРИЯТИЙ, /blocks/vavt_ref_company/, fa-cubes<br>
КОНТАКТЫ, /blocks/vavt_contact/contact.php, fa-phone<br>
</p>');
        $mform->addElement('html', '<hr>');
        $mform->addElement('textarea', 'menulist', 'Список', 'wrap="virtual" rows="25" cols="90"');


        $this->add_action_buttons();
    }
    function validation($data, $files)
    {
        $errors = parent::validation($data, $files); // TODO: Change the autogenerated stub
        return $errors;
    }

}
