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
 * Tool for hierarchical numbering of question categories.
 *
 * @package    qbank_renumbercategory
 * @copyright  2016 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Adds renumbercategory item into question bank on front page.
 *
 * @param navigation_node $coursenode navigation node object
 * @param stdClass $course frontpage course object
 * @param context $context frontpage course context object
 */
function qbank_renumbercategory_extend_navigation_frontpage(navigation_node $coursenode, stdClass $course,
        context $context) {
    qbank_renumbercategory_extend_navigation_course($coursenode, $course, $context);
}

/**
 * Adds renumbercategory item into question bank on course page.
 *
 * @param navigation_node $coursenode navigation node object
 * @param stdClass $course course object
 * @param context $context course context object
 */
function qbank_renumbercategory_extend_navigation_course(navigation_node $coursenode, stdClass $course,
        context $context) {
    global $CFG, $PAGE;

    if ($CFG->version >= 2023100900.00) { // Moodle 4.3
        // Since Moodle 4.3 question bank action menu can show plugins.
        return;
    }

    if (!has_capability('moodle/question:managecategory', $context)) {
        return;
    }
    $url = new moodle_url('/question/bank/renumbercategory/renumber.php', ['courseid' => $context->instanceid]);
    $coursenode->add(get_string('qbankrenumbercategory', 'qbank_renumbercategory'), $url, navigation_node::TYPE_SETTING,
            null, 'qbankrenumbercategory');

    // Quiz module navigation.
    if (!$PAGE->cm || $PAGE->cm->modname != 'quiz') {
        return;
    }
    $context = $PAGE->cm->context;
    if (!has_capability('moodle/question:managecategory', $context)) {
        return;
    }
    $parentnode = $coursenode->parent->get('modulesettings');
    $url = new moodle_url('/question/bank/renumbercategory/renumber.php', ['cmid' => $context->instanceid]);
    $parentnode->add(get_string('qbankrenumbercategory', 'qbank_renumbercategory'), $url, navigation_node::TYPE_SETTING,
        null, 'qbankrenumbercategory');
}
