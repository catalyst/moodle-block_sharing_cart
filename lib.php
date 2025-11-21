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
 *  Sharing Cart
 *
 * @package    block_sharing_cart
 * @copyright  2017 (C) VERSION2, INC.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Remove sharing cart entity, when related file was removed from the system
 * @param object $file file record
 * @throws dml_exception
 */
function block_sharing_cart_after_file_deleted($file) {
    global $DB;

    $cleaner = new \block_sharing_cart\files\cleaner($DB, $file);
    $cleaner->remove_related_sharing_cart_entity();
}

/**
 * Extend navigation for theme_snap.
 * @param cm_info $cm
 * @return array
 */
function block_sharing_cart_extend_module_editing_buttons(\cm_info $cm): array {
    // Check that user has capability to use sharing cart (e.g. backup/restore or whatever).
    global $PAGE, $COURSE, $USER;
    if ($USER->editing) {
        $sectionsjs = [];
        $sections = get_fast_modinfo($COURSE)->get_section_info_all();
        foreach ($sections as $section) {
            $newsection = new stdClass();
            $newsection->id = $section->id;
            $newsection->num = $section->section;
            $newsection->name = $section->name;
            array_push($sectionsjs, $newsection);
        }
        $params = [
                'course' => [
                        'id' => $COURSE->id,
                        'is_frontpage' => ($COURSE->id == SITEID),
                ],
                'sectionsjs' => $sectionsjs,
                'courseSections' => $cm,
                'add_method' => get_config('block_sharing_cart', 'add_to_sharing_cart'),
                'iconBackup' => [
                        'pix' => 't/copy',     // Moodle core icon identifier.
                        'css' => 'editing_backup',
                ],
                'lazy' => get_config('theme_snap', 'coursepartialrender'),
        ];

        $PAGE->requires->js_call_amd(
                'block_sharing_cart/integrate_snap', 'init', [$params]);
    }

    return [];
}
