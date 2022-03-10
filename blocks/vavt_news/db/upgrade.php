<?php

function xmldb_block_vavt_news_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    upgrade_block_savepoint(true, 2022031000, 'vavt_news', false);

    return true;
}

?>