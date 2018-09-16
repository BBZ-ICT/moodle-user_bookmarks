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
 * Version details
 *
 * @package    block
 * @subpackage block_my_enrolled_courses
 * @copyright  Dualcube (http://dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

defined('MOODLE_INTERNAL') || die();

// @TODO convert to classes/
require_once('functions.php');

global $DB, $CFG, $USER, $PAGE;

$SITE = $DB->get_record('course', ['id' => optional_param('courseid', SITEID, PARAM_INT)], '*', MUST_EXIST);
$contextid = required_param('contextid', PARAM_INT);
$url = new moodle_url($CFG->wwwroot . '/blocks/my_enrolled_courses/showhide.php', ['contextid' => $contextid]);
list($context, $course, $cm) = get_context_info_array($contextid);

require_login($SITE, false, $cm);

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add(get_string('block_name', 'block_my_enrolled_courses'), $url);
$PAGE->set_title($SITE->shortname . ': ' . get_string('block_name', 'block_my_enrolled_courses') . ': ' .
    get_string('showhide_page_title', 'block_my_enrolled_courses'));
$PAGE->set_heading($SITE->fullname . ': ' . get_string('block_name', 'block_my_enrolled_courses'));

$PAGE->requires->js('/blocks/my_enrolled_courses/js/jquery-1.10.2.js');
$PAGE->requires->js('/blocks/my_enrolled_courses/js/button-disable.js');
$PAGE->requires->css('/blocks/my_enrolled_courses/style.css');

$data = data_submitted();

// Show selected courses.
if (optional_param('show', false, PARAM_BOOL) && confirm_sesskey()) {
    if (isset($data->hidden)) {
        block_my_enrolled_courses_show_courses($data->hidden);
    }
}

// Hide selected courses.
if (optional_param('hide', false, PARAM_BOOL) && confirm_sesskey()) {
    if (isset($data->visible)) {
        block_my_enrolled_courses_hide_courses($data->visible);
    }
}

echo $OUTPUT->header();
// Print heading.
echo $OUTPUT->heading(get_string('showhide_page_title', 'block_my_enrolled_courses'));

// @TODO upgrade to mustache.
$html = '';
$html .= html_writer::start_tag('div', ['id' => 'showhide_section']);
$html .= html_writer::start_tag('form', ['id' => 'showhide_form', 'method' => 'post', 'action' => $url]);
$html .= html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
$html .= html_writer::start_tag('table', [
    'id' => 'showhidecourses',
    'class' => 'generaltable block_my_enrolled_courses',
]);
$html .= html_writer::start_tag('tr');
$html .= html_writer::start_tag('td', ['id' => 'visiblecourses', 'class' => 'block_my_enrolled_courses']);
$html .= html_writer::start_tag('div');
$html .= html_writer::start_tag('lable', ['for' => 'visible']);
$html .= html_writer::start_tag('b');
$html .= get_string('visible_lable', 'block_my_enrolled_courses');
$html .= html_writer::end_tag('b');
$html .= html_writer::end_tag('lable');
$html .= html_writer::end_tag('div');
$html .= html_writer::start_tag('div');
$html .= html_writer::start_tag('select', [
    'name' => 'visible[]',
    'id' => 'visible',
    'multiple' => 'multiple',
    'size' => 20,
]);
$html .= block_my_enrolled_courses_get_visible_courses();
$html .= html_writer::end_tag('select');
$html .= html_writer::end_tag('div');
$html .= html_writer::end_tag('td');
$html .= html_writer::start_tag('td', ['id' => 'showorhide', 'class' => 'block_my_enrolled_courses']);
$html .= html_writer::start_tag('div', ['id' => 'showbtn', 'class' => 'block_my_enrolled_courses']);
$html .= html_writer::empty_tag('input', [
    'type' => 'submit',
    'name' => 'show',
    'id' => 'show',
    'value' => $OUTPUT->larrow() . get_string('showcourse', 'block_my_enrolled_courses'),
]);
$html .= html_writer::end_tag('div');
$html .= html_writer::start_tag('div', ['id' => 'hidebtn', 'class' => 'block_my_enrolled_courses']);
$html .= html_writer::empty_tag('input', [
    'type' => 'submit',
    'name' => 'hide',
    'id' => 'hide',
    'value' => $OUTPUT->rarrow() . get_string('hidecourse', 'block_my_enrolled_courses'),
]);
$html .= html_writer::end_tag('div');
$html .= html_writer::end_tag('td');
$html .= html_writer::start_tag('td', ['id' => 'hiddencourses', 'class' => 'block_my_enrolled_courses']);
$html .= html_writer::start_tag('div');
$html .= html_writer::start_tag('lable', ['for' => 'hidden']);
$html .= html_writer::start_tag('b');
$html .= get_string('hidden_lable', 'block_my_enrolled_courses');
$html .= html_writer::end_tag('b');
$html .= html_writer::end_tag('lable');
$html .= html_writer::end_tag('div');
$html .= html_writer::start_tag('div');
$html .= html_writer::start_tag('select', [
    'name' => 'hidden[]',
    'id' => 'hidden',
    'multiple' => 'multiple',
    'size' => 20,
]);
$html .= block_my_enrolled_courses_get_hidden_courses();
$html .= html_writer::end_tag('select');
$html .= html_writer::end_tag('div');
$html .= html_writer::end_tag('td');
$html .= html_writer::end_tag('tr');
$html .= html_writer::end_tag('table');
$html .= html_writer::end_tag('form');
$html .= html_writer::end_tag('div');
echo $html;

echo $OUTPUT->footer();