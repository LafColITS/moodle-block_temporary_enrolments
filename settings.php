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
 * temporary_enrolments block settings
 *
 * @package    block_temporary_enrolments
 * @copyright  2018 onwards Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Check for required plugin Temporary Enrolments.
    if (!property_exists($CFG, 'local_temporary_enrolments_onoff') || !$CFG->local_temporary_enrolments_onoff) {
        $settings->add(new admin_setting_heading(
            'block_temporary_enrolments_warning',
            '',
            get_string('warning_admin_level', 'block_temporary_enrolments')));
    }

    $thresholds = array(
        get_string('settings:thresholds:option_off'),
        get_string('settings:thresholds:option_1'),
        get_string('settings:thresholds:option_2'),
        get_string('settings:thresholds:option_3'),
        get_string('settings:thresholds:option_4'),
        get_string('settings:thresholds:option_5'),
        get_string('settings:thresholds:option_6'),
        get_string('settings:thresholds:option_7'),
    );

    $settings->add(new admin_setting_configselect(
        'block_temporary_enrolments_urgent_threshold',
        get_string('admin_urgent_threshold_desc', 'block_temporary_enrolments'),
        get_string('admin_urgent_threshold_subdesc', 'block_temporary_enrolments'),
        3,
        $thresholds));

    $settings->add(new admin_setting_configtext(
        'block_temporary_enrolments_student_message',
        get_string('admin_student_message_desc', 'block_temporary_enrolments'),
        get_string('admin_student_message_subdesc', 'block_temporary_enrolments'),
        get_string('student_message_default', 'block_temporary_enrolments'),
        $paramtype = PARAM_RAW,
        $size = 50));
}
