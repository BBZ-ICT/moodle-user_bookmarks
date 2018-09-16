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

require_login();

if ($bookmarkurl = htmlspecialchars_decode($_GET["bookmarkurl"])
    and $newtitle = $_GET["title"] and confirm_sesskey()) {

    if (get_user_preferences('user_bookmarks')) {

        $bookmarks = explode(',', get_user_preferences('user_bookmarks'));

        $bookmarkupdated = false;

        foreach ($bookmarks as $bookmark) {
            $tempBookmark = explode('|', $bookmark);
            if ($tempBookmark[0] == $bookmarkurl) {
                $keyToRemove = array_search($bookmark, $bookmarks);
                $newBookmark = $bookmarkurl . "|" . $newtitle;
                $bookmarks[$keyToRemove] = $newBookmark;
                $bookmarkupdated = true;
            }
        }

        if ($bookmarkupdated == false) {
            print_error('nonexistentbookmark', 'admin');
            die;
        }

        $bookmarks = implode(',', $bookmarks);
        set_user_preference('user_bookmarks', $bookmarks);

        global $CFG;
        header("Location: " . $CFG->wwwroot . $bookmarkurl);
        die;
    }

    print_error('nobookmarksforuser', 'admin');
    die;

} else {
    print_error('invalidsection', 'admin');
    die;
}
