<?php

function xmldb_block_vavt_ref_company_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    upgrade_block_savepoint(true, 2022042800, 'vavt_ref_company', false);

    return true;
}

?>