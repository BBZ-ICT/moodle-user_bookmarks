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
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for block_user_bookmarks.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // User_bookmarks savepoint reached.
        upgrade_block_savepoint(true, XXXXXXXXXX, 'user_bookmarks');
    }

    return $result;
}