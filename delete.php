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

require('../../config.php');
defined('MOODLE_INTERNAL') || die();

require_login();
$context = context_system::instance();
$PAGE->set_context($context);

if ($bookmarkurl = htmlspecialchars_decode($_GET["bookmarkurl"]) and confirm_sesskey()) {

    if (get_user_preferences('user_bookmarks')) {

        $bookmarks = explode(',', get_user_preferences('user_bookmarks'));

        $bookmarkremoved = false;

        foreach ($bookmarks as $bookmark) {
            $tempBookmark = explode('|', $bookmark);
            if ($tempBookmark[0] == $bookmarkurl) {
                $keyToRemove = array_search($bookmark, $bookmarks);
                unset($bookmarks[$keyToRemove]);
                $bookmarkremoved = true;
            }
        }

        if ($bookmarkremoved == false) {
            print_error(get_string('error:nonexistentbookmark', 'block_user_bookmarks'), 'block_user_bookmarks');
            die;
        }

        $bookmarks = implode(',', $bookmarks);
        set_user_preference('user_bookmarks', $bookmarks);

        global $CFG;
        header("Location: " . $CFG->wwwroot . $bookmarkurl);
        die;
    }

    print_error(get_string('error:nobookmarksforuser', 'block_user_bookmarks'), 'block_user_bookmarks');
    die;

} else {
    print_error(get_string('error:invalidsection', 'block_user_bookmarks'), 'block_user_bookmarks');
    die;
}

