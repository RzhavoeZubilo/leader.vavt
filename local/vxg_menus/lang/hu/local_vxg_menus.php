<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Page for adding new organisation
 *
 * @package    local_vxg_menus
 * @copyright  Veloxnet
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['pluginname'] = 'Vxg Menük';
$string['privacy:metadata'] = 'A Menük plugin nem tárol személyes adatokat.';

// Remove nodes.
$string['setting_removemyhomenode']            = 'Kezdőoldal elrejtése';

$string['setting_removemyhomenode_desc']       = 'Irányítópult vagy Portál kezdőoldala elrejtése a menüből attól függően, hogy a felhasználó, mit állított be a kezdőoldalának.';
$string['setting_removehomenode']              = 'Portál kezdőoldal elrejtése';
$string['setting_removehomenode_desc']         = 'Portál kezdőoldal elrejtése';
$string['setting_removecalendarnode']          = 'Naptár elrejtése';
$string['setting_removecalendarnode_desc']     = 'Naptár elrejtése.';
$string['setting_removeprivatefilesnode']      = 'Saját állományaim elrejtése';
$string['setting_removeprivatefilesnode_desc'] = 'Saját állományaim elrejtése.';
$string['setting_removemycoursesnode']         = 'Kurzusaim elrejtése';
$string['setting_removemycoursesnode_desc']    = 'Kurzusaim elrejtése.';

$string['setting_removeparticipantsnode']      = 'Résztvevők elrejtése';
$string['setting_removeparticipantsnode_desc'] = 'Résztvevők elrejtése.';
$string['setting_removebadgesnode']            = 'Kítűzők elrejtése';
$string['setting_removebadgesnode_desc']       = 'Kítűzők elrejtése (Csak akkor, ha a kurzusban nincs kitűzők).';

$string['setting_removecompetenciesnode']      = 'Készségek elrejtése';
$string['setting_removecompetenciesnode_desc'] = 'Készségek elrejtése (Csak akkor ha kurzusban nincs készségek).';
$string['setting_removegradesnode']            = 'Pontok elrejtése';
$string['setting_removegradesnode_desc']       = 'Pontok elrejtése.';

$string['hide_for_roles'] = 'Elrejtés annak aki';

// Headings.
$string['hide_side_menu_head'] = 'Menü elemek elrejtése meghatározott szerepkörrel rendelkező felhasználóknak. Ha nincs szerepkör meghatározva, a rendszergazdákon kívűl mindenkinek el lesz rejtve. De van lehetőség hogy a rendszergazdáknak is el lehessen rejteni.';
$string['hide_course_side_menu_head'] = 'Kurzus menük elrejtése.';

// Menus.
$string['hide_menus']                 = 'Menük elrejtése';
$string['setting_hide_to_admin']      = 'Elrejtés rendszergazdának';
$string['setting_hide_to_admin_desc'] = '<br><br><br>';
$string['side_menus']                 = 'Oldalsó menü';
$string['custom_menu_items']          = 'Saját menü elemek';

// Custome nodes.
$string['all_menu']  = 'Összes saját menü';
$string['name']      = 'Név';
$string['name_help'] = 'A felirat, ami megjelenik a menün';
$string['lang']      = 'Nyelv';
$string['lang_help'] = 'Nyelv kiválasztása esetén, a menü csak akkor jelenik meg a felhasználó számára, ha ezt a nyelvet használja.';
$string['url']       = 'URL';
$string['url_help']  = '<b>Példa</b><br>Ha az oldal: "https://moodlesite.com"<br> Felhasználó profilhoz az url "user/profile.php"';
$string['icon']      = 'Ikon';

$string['add_new']        = 'Hozzáadás';
$string['edit']           = 'Módosítás';
$string['delete']         = 'Törlés';
$string['delete_confirm'] = 'Biztos benne hogy törli?';
$string['disabled']       = 'Letíltás';
$string['disabled_help']       = 'Ha be van pipálva a menü nem jelenik meg.';
$string['roles']          = 'Szerepkörök';
$string['front']          = 'Írányítópult után';
$string['back']           = 'Az utolsó menü után';
$string['order']          = 'After ';
$string['urlparam']       = 'URL paramétere';
$string['urlparam_help']  = 'Ez hozzáadja az aktuális kurzus azonosítóját az URL végéhez a bejeleölt paraméter névvel.
Például, ha menüt szeretne készíteni a kurzus teljesítése oldalhoz, akkor az URL "course/completion.php" lesz, és az URL paraméternél az "id"-t kell bejeleölni.
Így, ha olyan kurzust néz, amelynek azonosítója 2, akkor a menü URL-je a "course/completion.php?id=2" lesz.<br>Ha valamelyik paraméter ben van jelölve a menü csak, akkor jelenik meg amikor egy kurzust nézel.';
$string['noparam']        = 'Paraméter nélkül';

// Icon-selection.
$string['select-icon'] = 'Ikon választása';
$string['iconselection'] = 'Ikon választó';
