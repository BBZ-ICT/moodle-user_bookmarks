<?php

function xmldb_qtype_myqtype_upgrade($oldversion = 0) {
    global $CFG, $DB;
    $dbman = $DB->get_manager();

    $result = true;

    if ($oldversion < 2014090502) {

        // Define table block_user_bookmarks to be created.
        $table = new xmldb_table('block_user_bookmarks');

        // Adding fields to table block_user_bookmarks.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);

        // Adding keys to table block_user_bookmarks.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_user_bookmarks.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // User_bookmarks savepoint reached.
        upgrade_block_savepoint(true, XXXXXXXXXX, 'user_bookmarks');
    }

    return $result;
}