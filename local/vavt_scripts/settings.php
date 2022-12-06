<?php
/**
 * User: densh
 * Date: 14.12.2021
 * Time: 11:56
 */
defined('MOODLE_INTERNAL') || die;

// Чтобы включить настройку, нужно добавить в класс
//function has_config() {
//    return true;
//}

    require_once("$CFG->libdir/resourcelib.php");

$ADMIN->add('vavtsettings', new admin_externalpage('setalumnifield', 'Поля выпускников', $CFG->wwwroot . '/blocks/vavt_fav_alumni/set_field_view.php'));

$ADMIN->add('vavtsettings', new admin_externalpage('setpictureevent', 'Изображение на странице мероприятий', $CFG->wwwroot . '/blocks/vavt_event/set_picture.php'));
$ADMIN->add('vavtsettings', new admin_externalpage('setbtnaddproject', 'Кнопка Предложить проект', $CFG->wwwroot . '/blocks/vavt_project/set_picture.php'));
$ADMIN->add('vavtsettings', new admin_externalpage('setbtnaddproject', 'Содержимое страницы "Чаты"', $CFG->wwwroot . '/local/vavt_scripts/set_content_chat_page.php'));
