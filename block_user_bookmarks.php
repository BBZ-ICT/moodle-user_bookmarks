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

/**
 * User Bookmarks Block page.
 *
 * @package    block
 * @subpackage user_bookmarks
 * Version details
 * @copyright  2012 Moodle
 * @author     Authors of Admin Bookmarks:-
 *               2006 vinkmar
 *               2011 Rossiani Wijaya (updated)
 *             Authors of User Bookmarks This Version:-
 *               2013 Jonas Rueegge
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

/**
 * The user bookmarks block class
 */
class block_user_bookmarks extends block_base {

    /** @var string */
    public $blockname = null;

    /** @var bool */
    protected $contentgenerated = false;

    /** @var bool|null */
    protected $docked = null;

    /**
     * Set the initial properties for the block
     */
    function init() {
        $this->blockname = get_class($this);
        $this->title = get_string('blocktitle', 'block_user_bookmarks');
    }

    /**
     * Are you going to allow multiple instances of each block?
     * If yes, then it is assumed that the block WILL USE per-instance configuration
     *
     * @return boolean
     */
    function instance_allow_multiple() {
        return false;
    }

    /**
     * Is each block of this type going to have instance-specific configuration?
     * Normally, this setting is controlled by {@link instance_allow_multiple()}: if multiple
     * instances are allowed, then each will surely need its own configuration. However, in some
     * cases it may be necessary to provide instance configuration to blocks that do not want to
     * allow multiple instances. In that case, make this function return true.
     * I stress again that this makes a difference ONLY if {@link instance_allow_multiple()} returns false.
     *
     * @return boolean
     */
    function instance_allow_config() {
        return true;
    }

    /**
     * Set the applicable formats for this block to all
     *
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    function applicable_formats() {
        if (has_capability('moodle/site:config', context_system::instance())) {
            return ['all' => true];
        } else {
            return ['site' => true];
        }
    }

    public function specialization() {
        if (!isset($this->config)) {
            $this->config = new stdClass();
            $this->config->title = get_string('blocktitle', 'block_user_bookmarks');
        }
    }

    /**
     * Gets the content for this block
     * Needed Strings for Multilingual Support for this function
     * avaiable via get_string(); (@JR2013)
     */
    function get_content() {

        global $CFG, $PAGE;

        $this->config->title = get_string('blocktitle', 'block_user_bookmarks');

        // First check if we have already generated, don't waste cycles.
        if ($this->contentgenerated === true) {
            return $this->content;
        }
        $this->content = new stdClass();

        // @TODO inline scripting? should be updated.
        $noscript = '<noscript>' . get_string('error:noscript', 'block_user_bookmarks') . '</noscript>';
        $javascript = '<script type="text/javascript">
                            function updateBookmark(bookmarkURL, defaultTitle, sesskey, wwwroot) {
                                var newBookmarkTitle = prompt(\'' . get_string('editbookmarktitle', 'block_user_bookmarks') . '\',defaultTitle);
                                if (newBookmarkTitle == "" || newBookmarkTitle == null) {
                                newBookmarkTitle = defaultTitle;
                                }else {
                                var redirectPage = wwwroot + "/blocks/user_bookmarks/update.php?bookmarkurl=" + escape(bookmarkURL) 
                                         + "&title=" + encodeURIComponent(newBookmarkTitle) + "&sesskey=" + sesskey;
                                window.location = redirectPage;
                                }
                            }
                            function deleteBookmark(bookmarkURL, sesskey, wwwroot) {
                                var redirectPage = wwwroot + "/blocks/user_bookmarks/delete.php?bookmarkurl=" 
                                         + escape(bookmarkURL) + "&sesskey=" + sesskey;
                                window.location = redirectPage;
                            }
                            function addBookmark(bookmarkURL, defaultTitle, sesskey, wwwroot) {
                                var newBookmarkTitle = prompt(\'' . get_string('enterbookmarktitle', 'block_user_bookmarks') . '\',defaultTitle);
                                if (newBookmarkTitle == "" || newBookmarkTitle == null) {
                                       newBookmarkTitle = defaultTitle;
                                } else {
                                    var redirectPage = wwwroot + "/blocks/user_bookmarks/create.php?bookmarkurl=" + escape(bookmarkURL) 
                                             + "&title=" + encodeURIComponent(newBookmarkTitle) + "&sesskey=" + sesskey;
                                    window.location = redirectPage;
                                }
                            }
                          </script>';

        if (get_user_preferences('user_bookmarks')) {
            require_once($CFG->libdir . '/adminlib.php');

            $tempbookmarks = explode(',', get_user_preferences('user_bookmarks'));
            /// Accessibility: markup as a list.
            $contents = [];

            // @TODO could be done with mustache.
            foreach ($tempbookmarks as $bookmark) {
                // The bookmarks are in the following format- url|title.
                // So exploading the bookmark by "|" to get the url and title.
                $tempBookmark = explode('|', $bookmark);

                // Making the url for bookmark.
                $contenturl = new moodle_url($CFG->wwwroot . $tempBookmark[0]);

                // Now making a link.
                $contentlink = html_writer::link($contenturl, $tempBookmark[1]);

                // This is the url to delete bookmark.
                $bookmarkdeleteurl = new moodle_url('/blocks/user_bookmarks/delete.php', [
                    'bookmarkurl' => $tempBookmark[0],
                    'sesskey' => sesskey(),
                ]);

                // This has the link to delete the bookmark.
                $deleteLink = '<a class="delete" href="' . $bookmarkdeleteurl . '">
                                     <i class="fa fa-remove" title="' .
                    get_string('deletebookmark', 'block_user_bookmarks') . '"></i>
                                </a>';

                // Creating the link to update the title for bookmark.
                // @TODO convert to a more Moodle way.
                $editLink = '<a class="edit" href="#" onClick="updateBookmark(\''
                    . $tempBookmark[0] . '\', \'' . $tempBookmark[1] . '\', \'' . sesskey() . '\', \'' . $CFG->wwwroot . '\');">
                                            <i class="fa fa-edit" title="' . get_string('editbookmark', 'block_user_bookmarks') . '" ></i>
                            </a>';

                // Setting layout for the bookmark and its delete and edit buttons.
                $contents[] = html_writer::tag('li', $contentlink . " " . $editLink . " " . $deleteLink);
                $bookmarks[] = html_entity_decode($tempBookmark[0]);
            }
            $this->content->text = html_writer::tag('ol', implode('', $contents), ['class' => 'list']);
        } else {
            $bookmarks = [];
        }

        $this->content->footer = '';
        $this->page->settingsnav->initialise();

        $bookmarkurl = htmlspecialchars_decode(str_replace($CFG->wwwroot, '', $PAGE->url));
        $bookmarktitle = $PAGE->title;

        if (in_array($bookmarkurl, $bookmarks)) {
            // This prints out the link to unbookmark a page.
            $this->content->footer = $javascript . $noscript . '
                    <form style="cursor: hand;">
                    <a class="delete-bookmark btn btn-default"  onClick="deleteBookmark(\'' . $bookmarkurl . '\', \'' . sesskey() . '\', \'' . $CFG->wwwroot . '\');">'
                . get_string('deletebookmarkthissite', 'block_user_bookmarks') . '</a>
                    </form>';
        } else {
            // @TODO inline styling is slower then external css.
            // This prints out link to bookmark a page.
            $this->content->footer = $javascript . '
                        <form>
                        <a class="add-bookmark btn btn-default" onClick="addBookmark(\'' . $bookmarkurl . '\', \'' . $bookmarktitle . '\', \'' . sesskey() . '\', \'' . $CFG->wwwroot . '\');">'
                . get_string('bookmarkpage', 'block_user_bookmarks') . '</a>
                        </form>';
        }

        return $this->content;
    }
}

