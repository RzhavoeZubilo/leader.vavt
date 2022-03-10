<?php

function xmldb_block_vavt_contact_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    upgrade_block_savepoint(true, 2022031001, 'vavt_contact', false);

    return true;
}

?>