<?php

require('../../config.php');

require_login();
$context = context_system::instance();
$PAGE->set_context($context);

if ($bookmarkurl = htmlspecialchars_decode($_GET["bookmarkurl"]) and confirm_sesskey()) {

    if (get_user_preferences('user_bookmarks')) {

        $bookmarks = explode(',', get_user_preferences('user_bookmarks'));

        $bookmarkremoved = false;

        foreach($bookmarks as $bookmark) {
            $tempBookmark = explode('|', $bookmark);
            if ($tempBookmark[0] == $bookmarkurl) {
                $keyToRemove = array_search($bookmark, $bookmarks);
                unset($bookmarks[$keyToRemove]);
                $bookmarkremoved = true;
            }
        }
        
        if ($bookmarkremoved == false) {
             print_error(get_string('error:nonexistentbookmark', 'block_user_bookmarks'), 'admin');
            die;
        }
        
        $bookmarks = implode(',', $bookmarks);
        set_user_preference('user_bookmarks', $bookmarks);
        
        global $CFG;
        header("Location: " . $CFG->wwwroot . $bookmarkurl);
        die;
    }

    print_error(get_string('error:nobookmarksforuser', 'block_user_bookmarks'), 'admin');
    die;

} else {
    print_error(get_string('error:invalidsection', 'block_user_bookmarks'), 'admin');
    die;
}

