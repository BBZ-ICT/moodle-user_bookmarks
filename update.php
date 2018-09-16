<?php
/**
 * Created on Jul 20, 2012
 * 
 * @author Gurvinder Singh
 */

require('../../config.php');

require_login();

if ($bookmarkurl = htmlspecialchars_decode($_GET["bookmarkurl"])
    and $newtitle = $_GET["title"] and confirm_sesskey()) {

    if (get_user_preferences('user_bookmarks')) {

        $bookmarks = explode(',', get_user_preferences('user_bookmarks'));

        $bookmarkupdated = false;

        foreach($bookmarks as $bookmark) {
            $tempBookmark = explode('|', $bookmark);
            if ($tempBookmark[0] == $bookmarkurl) {
                $keyToRemove = array_search($bookmark, $bookmarks);
                $newBookmark = $bookmarkurl . "|" . $newtitle;
                $bookmarks[$keyToRemove] = $newBookmark;
                $bookmarkupdated = true;
            }
        }
        
        if ($bookmarkupdated == false) {
            print_error('nonexistentbookmark','admin');
            die;
        }
        
        $bookmarks = implode(',', $bookmarks);
        set_user_preference('user_bookmarks', $bookmarks);
        
        global $CFG;
        header("Location: " . $CFG->wwwroot . $bookmarkurl);
        die;
    }

    print_error('nobookmarksforuser','admin');
    die;

} else {
    print_error('invalidsection', 'admin');
    die;
}
