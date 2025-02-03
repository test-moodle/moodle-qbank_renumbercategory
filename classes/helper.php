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

namespace qbank_renumbercategory;

use context;
use core\event\question_category_updated;

/**
 * Class representing custom question category
 *
 * @package    qbank_renumbercategory
 * @copyright  2016 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {

    /**
     * Number category and all subcategories.
     *
     * @param string $categorypluscontext
     * @param string $prefix
     */
    public static function renumber_category(string $categorypluscontext, string $prefix = '') {
        $parts = explode(',', $categorypluscontext);
        $categoryid = $parts[0];
        $contextid = $parts[1];
        $context = context::instance_by_id($contextid);
        require_capability('moodle/question:managecategory', $context);
        static::renumber_category_recursive($categoryid, $context, $prefix);
    }

    /**
     * Number category and all subcategories.
     *
     * @param int $categoryid
     * @param context $context
     * @param string $prefix
     */
    private static function renumber_category_recursive(int $categoryid, context $context, string $prefix = '') {
        global $DB;

        $subcategories = $DB->get_records('question_categories',
                ['parent' => $categoryid, 'contextid' => $context->id], 'sortorder ASC, name ASC');
        $significantdigits = floor(log(count($subcategories), 10)) + 1;
        $sortorder = 1;
        foreach ($subcategories as $subcategory) {
            $sortorderstring = (string) $sortorder;
            $newprefix = $prefix . str_pad($sortorderstring, $significantdigits, '0', STR_PAD_LEFT) . '.';
            $newcategoryname = ltrim($subcategory->name, '0123456789. ');
            $newcategoryname = $newprefix . ' ' . $newcategoryname;
            $DB->update_record('question_categories', $subcategory);
            if ($subcategory->name != $newcategoryname) {
                $subcategory->name = $newcategoryname;
                $DB->update_record('question_categories', $subcategory);
                $event = question_category_updated::create_from_question_category_instance($subcategory, $context);
                $event->trigger();
            }
            static::renumber_category_recursive($subcategory->id, $context, $newprefix);
            $sortorder++;
        }
    }

    /**
     * Remove numbers from category and all subcategories.
     *
     * @param string $categorypluscontext
     */
    public static function unnumber_category(string $categorypluscontext) {
        $parts = explode(',', $categorypluscontext);
        $categoryid = $parts[0];
        $contextid = $parts[1];
        $context = context::instance_by_id($contextid);
        require_capability('moodle/question:managecategory', $context);
        static::unnumber_category_recursive($categoryid, $context);
    }

    /**
     * Remove numbers from category and all subcategories.
     *
     * @param int $categoryid
     * @param int $contextid
     */
    private static function unnumber_category_recursive(int $categoryid, context $context) {
        global $DB;

        $subcategories = $DB->get_records('question_categories',
                ['parent' => $categoryid, 'contextid' => $context->id]);
        foreach ($subcategories as $subcategory) {
            $newcategoryname = ltrim($subcategory->name, '0123456789. ');
            if ($subcategory->name != $newcategoryname) {
                $subcategory->name = $newcategoryname;
                $DB->update_record('question_categories', $subcategory);
                $event = question_category_updated::create_from_question_category_instance($subcategory, $context);
                $event->trigger();
            }
            static::unnumber_category_recursive($subcategory->id, $context);
        }
    }

}
