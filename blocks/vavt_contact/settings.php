<?php
/**
 * User: densh
 * Date: 14.12.2021
 * Time: 11:56
 */
defined('MOODLE_INTERNAL') || die;

//$settings = new admin_settingpage('local_edu', 'Электронный деканат');
//$ADMIN->add('localplugins', $settings);

// Чтобы включить настройку, нужно добавить в класс
//function has_config() {
//    return true;
//}

if ($ADMIN->fulltree) {
    require_once("$CFG->libdir/resourcelib.php");

    // Presentation options heading.
    $settings->add(new admin_setting_heading('block_vavt_contact', 'Контакты. Связаться с нами', 'Контакты. Связаться с нами'));
    $settings->add(new admin_setting_configtext('block_vavt_contact/community', "Сообщество", "", ''));
    $settings->add(new admin_setting_configtext('block_vavt_contact/support', "Служба ТП", "", ''));
    $settings->add(new admin_setting_configtext('block_vavt_contact/paidprograms', "Платные программы", "", ''));

}