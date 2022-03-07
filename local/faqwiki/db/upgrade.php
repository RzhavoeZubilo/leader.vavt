<?php

function xmldb_local_faqwiki_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    upgrade_plugin_savepoint(true, 2022022800, 'local', 'faqwiki');
    return true;
}

?>