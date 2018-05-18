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
 * Temporary Enrolments Block tests.
 *
 * @package    block_temporary_enrolments
 * @copyright  2018 onwards Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot. '/blocks/temporary_enrolments/lib.php');

class block_temporary_enrolments_testcase extends advanced_testcase {
    /**
     * Check that convert_duration function works properly.
     *
     * @return void
     */
    public function test_convert_duration() {
        $expecteds = array(
            'pineapple' => false,
            -100        => false,
            1           => "Less than a day",
            43199       => "Less than a day",
            43200       => "1&nbsp;day",
            86399       => "1&nbsp;day",
            86400       => "1&nbsp;day",
            86401       => "1&nbsp;day",
            129599      => "1&nbsp;day",
            129600      => "2&nbsp;days",
            172799      => "2&nbsp;days",
            172800      => "2&nbsp;days",
            172801      => "2&nbsp;days",
            432000      => "5&nbsp;days",
            518400      => "6&nbsp;days",
            561599      => "6&nbsp;days",
            561600      => "1&nbsp;week",
            604800      => "1&nbsp;week",
            691200      => "1&nbsp;week 1&nbsp;day",
            1080000     => "1&nbsp;week 6&nbsp;days",
            1192320     => "2&nbsp;weeks",
            1382000     => "2&nbsp;weeks 2&nbsp;days",
        );

        foreach ($expecteds as $input => $output) {
            $result = convert_duration($input);
            $this->assertEquals($result, $output);
        }
    }
}
