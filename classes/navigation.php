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
 * @copyright  2025 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace qbank_renumbercategory;

use core_question\local\bank\navigation_node_base;

/**
 * Class navigation.
 *
 * Plugin entrypoint for navigation.
 *
 * @package    qbank_renumbercategory
 * @copyright  2025 Vadim Dvorovenko <Vadimon@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class navigation extends navigation_node_base {

    /**
     * Title for this node.
     */
    public function get_navigation_title(): string {
        return get_string('renumbercategory', 'qbank_renumbercategory');
    }

    /**
     * Key for this node.
     */
    public function get_navigation_key(): string {
        return 'renumbercategory';
    }

    /**
     * URL for this node
     */
    public function get_navigation_url(): \moodle_url {
        return new \moodle_url('/question/bank/renumbercategory/renumber.php');
    }

    /**
     * Tab capabilities.
     *
     * If it has capabilities to be checked, it will return the array of capabilities.
     *
     * @return null|array
     */
    public function get_navigation_capabilities(): ?array {
        return ['moodle/question:managecategory'];
    }
}
