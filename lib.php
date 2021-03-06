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
 * Temporary Enrolments block lib functions.
 *
 * @package    block_temporary_enrolments
 * @copyright  2018 onwards Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Parse a number of seconds into a string stating the duration in weeks and days.
 *
 * @param int $seconds Number of seconds to convert.
 *
 * @return string The string representing the duration.
 */
function convert_duration($seconds) {
    if (!is_numeric($seconds) || $seconds < 0) {
        return false;
    }
    $seconds = round($seconds / 86400) * 86400; // Round to nearest days.
    if ($seconds < 86400) {
        $converted = get_string('content:duration:less_than_a_day', 'block_temporary_enrolments');
    } else {
        $weeks = floor($seconds / 604800);
        $days = round(($seconds % 604800) / 86400);
        $sweeks = "";
        $sdays = "";
        if ($weeks == 1) {
            $sweeks = '1&nbsp;' . get_string('content:duration:week_singular', 'block_temporary_enrolments') . ' ';
        } else if ($weeks > 1) {
            $sweeks = "$weeks&nbsp;" . get_string('content:duration:week_plural', 'block_temporary_enrolments') . ' ';
        }
        if ($days == 1) {
            $sdays = '1&nbsp;' . get_string('content:duration:day_singular', 'block_temporary_enrolments');
        } else if ($days > 1) {
            $sdays = "$days&nbsp;" . get_string('content:duration:day_plural', 'block_temporary_enrolments');
        }
        $converted = trim($sweeks . $sdays);
    }

    return $converted;
}
