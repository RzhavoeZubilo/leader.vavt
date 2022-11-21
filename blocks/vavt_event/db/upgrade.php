<?php

function xmldb_block_vavt_event_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    upgrade_block_savepoint(true, 2022110100, 'vavt_event', false);

    return true;
}

?>