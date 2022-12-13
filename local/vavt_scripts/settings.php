<?php

defined('MOODLE_INTERNAL') || die;

// Чтобы включить настройку, нужно добавить в класс
//function has_config() {
//    return true;
//}

    require_once("$CFG->libdir/resourcelib.php");

if (is_null($ADMIN->locate('vavtsettings'))) {
    $ADMIN->add('root', new admin_category('vavtsettings', 'ВАВТ НАСТРОЙКИ'));
}

$ADMIN->add('vavtsettings', new admin_externalpage('setalumnifield', 'Поля выпускников справочнике', $CFG->wwwroot . '/blocks/vavt_fav_alumni/set_field_view.php'));
$ADMIN->add('vavtsettings', new admin_externalpage('sidemenuset', 'Настройка меню', $CFG->wwwroot . '/local/vavt_scripts/set_menu.php'));

$ADMIN->add('vavtsettings', new admin_category('allset', 'Настройки страниц'));

$ADMIN->add('allset', new admin_externalpage('setbtnaddproject', 'Содержимое страницы "Чаты"', $CFG->wwwroot . '/local/vavt_scripts/set_content_chat_page.php'));
$ADMIN->add('allset', new admin_externalpage('setbtnaddproject', 'Содержимое страницы "Контакты"', $CFG->wwwroot . '/blocks/vavt_contact/set_content_contact_page.php'));

$ADMIN->add('allset', new admin_externalpage('setpictureevent', 'Изображение на странице мероприятий', $CFG->wwwroot . '/blocks/vavt_event/set_picture.php'));
$ADMIN->add('allset', new admin_externalpage('setbtnaddproject', 'Кнопка Предложить проект (на странице Проекты сообщества)', $CFG->wwwroot . '/blocks/vavt_project/set_picture.php'));
