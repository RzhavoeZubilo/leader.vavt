<?php

defined('MOODLE_INTERNAL') || die;

// Чтобы включить настройку, нужно добавить в класс
//function has_config() {
//    return true;
//}

    require_once("$CFG->libdir/resourcelib.php");
    //
    //if (is_null($ADMIN->locate('vavtsettings'))) {
    //    $ADMIN->add('root', new admin_category('vavtsettings', 'ВАВТ НАСТРОЙКИ'));
    //}
    ////-------------------------------------------------------------------
    //$ADMIN->add('vavtsettings', new admin_category('blockcontact', 'Блок контакты'));
    //
    //// настройка через шестеренку настройки блока
    //$ADMIN->add('blockcontact', new admin_externalpage('blockcontactset', 'Настройка блока Контакты (справа на главной)', $CFG->wwwroot . '/?bui_editid='));
    ////-------------------------------------------------------------------
    //
    //
    //
    //$ADMIN->add('vavtsettings', new admin_category('contact_vavt', 'Контакты ВАВТ'));
    //
    //$settings = new admin_settingpage('block_vavt_contact', 'Страница контактов');
    //$ADMIN->add('vavtsettings', $settings);
    //
    //$settings->add(new admin_setting_heading('block_vavt_contact_1', 'Раздел #1',
    //    'Отображается на странице Контакты'));
    //$ADMIN->add('block_vavt_contact', $settings);
    //
    //$settings->add(new admin_setting_configtextarea('block_vavt_contact_1/text1', "Текст первого блока", "", '', PARAM_RAW));
    //
    //$settings->add(new admin_setting_configtext('block_vavt_contact_1/phone', "Телефон для вступления", "Телефоны можно ввести через запятую", ''));
    //$settings->add(new admin_setting_configtext('block_vavt_contact_1/mail', "Почта для вступления", "Телефоны можно ввести через запятую", ''));
    //
    //$settings->add(new admin_setting_heading('block_vavt_contact_2', 'Раздел #2',
    //    'Отображается на странице Контакты'));
    //$ADMIN->add('block_vavt_contact', $settings);
    //
    //$settings->add(new admin_setting_configtextarea('block_vavt_contact_1/text2', "Текст второго блока", "", '', PARAM_RAW));
    //$settings->add(new admin_setting_configtext('block_vavt_contact_2/nameadmin', "Имя администратора", "", ''));
    //$settings->add(new admin_setting_configtext('block_vavt_contact_2/phone', "Телефоны второго блока", "Телефоны можно ввести через запятую", ''));
    //$settings->add(new admin_setting_configtext('block_vavt_contact_2/mail', "Почта второго блока", "", ''));
    //$settings->add(new admin_setting_configtext('block_vavt_contact_2/site', "Сайт", "", ''));
    //$settings->add(new admin_setting_configtextarea('block_vavt_contact_2/address', "Адрес", "", '', PARAM_RAW));
    //$settings->add(new admin_setting_configtextarea('block_vavt_contact_2/maps', "iframe Яндекс.Карты", "", '', PARAM_RAW));
