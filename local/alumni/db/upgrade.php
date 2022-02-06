<?php

function xmldb_local_alumni_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    upgrade_plugin_savepoint(true, 2022012000, 'local', 'alumni');
    return true;
}

?>