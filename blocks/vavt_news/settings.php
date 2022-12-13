<?php
/**
 * User: densh
 * Date: 14.12.2021
 * Time: 11:56
 */

defined('MOODLE_INTERNAL') || die;


// Данные настройки будут применены ко ВСЕМ блокам!

// можно добавить отдельную вкладку в администрировании для настроек
//$ADMIN->add('root', new admin_category('vavtsettings', 'ВАВТ НАСТРОЙКИ'));
//$ADMIN->add('vavtsettings', new admin_category('blockcontact', 'Блок контакты'));
//$settings = new admin_settingpage('local_edu', 'Новости');
//$ADMIN->add('localplugins', $settings);


// Чтобы включить настройку, нужно добавить в класс плагина/блока
/*
function has_config() {
    return true;
}
*/

//if ($ADMIN->fulltree) {
//    require_once("$CFG->libdir/resourcelib.php");
//    // Добавляет ссылку в Администрирование/Плагины/Блоки
//    $settings->add(new admin_setting_heading('block_vavt_news', 'Новости', 'Настройка блока новости'));
//    $settings->add(new admin_setting_configtext('block_vavt_news/cntitem', "Кол-во новостей в блоке", "", 3));
//}


//if (is_null($ADMIN->locate('vavtsettings'))) {
//    $ADMIN->add('root', new admin_category('vavtsettings', 'ВАВТ НАСТРОЙКИ'));
//}
// Страница настроек задана в блоке vavt_contact

//$settings = new admin_settingpage('block_vavt_news', 'Блок Новости');
//$ADMIN->add('vavtsettings', $settings);

$settings->add(new admin_setting_heading('block_vavt_contact_sett', 'Настройки блока Новости',
    'Отображается на главной и ЛК'));
$ADMIN->add('block_vavt_news', $settings);

$settings->add(new admin_setting_configtext('block_vavt_contact_sett/cntitem', "Кол-во новостей в блоке", "", 3));

// чтобы получить значения настроек
/*
$mysetting = get_config("block_vavt_news");
if($mysetting->cntitem == 1) ...
*/