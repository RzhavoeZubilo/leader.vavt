<?php
require_once("../../config.php");

global $ORG, $DB, $USER;

if (optional_param('json', 0, PARAM_INT)) {
    header('Content-Type: application/json');
}

$action = optional_param('action', '', PARAM_TEXT);

if ($action == 'favoriteadd') {
    $obj = new \stdClass();
    $obj->nameplugin = $_POST['plugin'];
    $obj->objid = $_POST['objid'];
    $obj->usermodified = $USER->id;
    $obj->timecreated = time();

    $DB->insert_record('vavt_favorite', $obj);
}

if ($action == 'favoritedel') {
    $DB->delete_records('vavt_favorite', array('objid' => $_POST['objid'],
        'nameplugin' => $_POST['plugin'],
        'usermodified' => $USER->id));
}

if ($action == 'get_category') {
    $data = $DB->get_records('vavt_ref_category', ['industry'=>trim($_POST['industry'])]);
    $html='';
    foreach ($data as $d){
        $namecat = trim($d->name);
        $html .=  "<optgroup label='$namecat'>";
        //$html .=  "'$d->name'";
        $rows = $DB->get_records_sql("SELECT * FROM mdl_vavt_ref_subcategory WHERE name_cat like '%{$namecat}%'");
        foreach ($rows as $r){
            $html.=  "<option value='$r->name_subcat'>$r->name_subcat</option>";
        }
        $html.="</optgroup>";
    }

    //echo json_encode($html);
    echo $html;
}