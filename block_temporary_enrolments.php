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
 * Temporary Enrolments Block.
 *
 * @package    block_temporary_enrolments
 * @copyright  2018 onwards Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/blocks/temporary_enrolments/lib.php');
require_once($CFG->dirroot . '/local/temporary_enrolments/lib.php');

class block_temporary_enrolments extends block_base {

    public function init() {
        $this->title = get_string('title', 'block_temporary_enrolments');
    }

    public function applicable_formats() {
        return array('course-view' => true);
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function has_config() {
        return true;
    }

    public function get_content() {
        global $CFG, $DB, $COURSE, $USER;

        // Check to make sure local_temporary_enrolments is installed and enabled.
        if (!get_config('local_temporary_enrolments', 'onoff')) {
            return "";
        }

        if ($this->content !== null) {
            return $this->content;
        }

        require_once($CFG->libdir . '/filelib.php');

        $filteropt = new stdClass;
        $filteropt->overflowdiv = true;
        if ($this->content_is_trusted()) {
            // Fancy html allowed only on course, category and system blocks.
            $filteropt->noclean = true;
        }

        $data = array();

        $data['threshold'] = ($CFG->block_temporary_enrolments_urgent_threshold) * 86400;
        $data['message'] = $CFG->block_temporary_enrolments_student_message;
        $data['context'] = context_course::instance($COURSE->id);
        $data['role'] = get_temp_role();

        $this->content = new stdClass();
        $this->content->footer = '';

        if (has_capability('block/temporary_enrolments:canviewall', $data['context'])) {
            $this->content->text = $this->make_table($data);
        } else {
            $roleassignment = $DB->get_record('role_assignments', array(
                'userid' => $USER->id,
                'contextid' => $data['context']->id));

            if ($roleassignment && $roleassignment->roleid == $data['role']->id) {
                $this->content->text = $this->make_message($data);
            }
        }

        unset($filteropt); // Memory footprint.

        return $this->content;
    }

    private function make_table($data) {
        global $DB;

        $tempusers = get_role_users($data['role']->id, $data['context'], true);

        $output = "";

        if (count($tempusers) == 0) {
            return "";
        }

        $userstring = get_string('content:admin_table:user', 'block_temporary_enrolments');
        $timeremainingstring = get_string('content:admin_table:time_remaining', 'block_temporary_enrolments');
        $output = "<table class=\"block_temporary_enrolments_table\">"
                . "<tr>"
                . "<th>$userstring</th>"
                . "<th>$timeremainingstring</th>"
                . "</tr>";

        foreach ($tempusers as $tempuser) {
            $roleassignment = $DB->get_record('role_assignments', array(
                'userid' => $tempuser->id,
                'contextid' => $data['context']->id));

            $expire = $DB->get_record('local_temporary_enrolments', array(
                'roleassignid' => $roleassignment->id));

            $t = $expire->timeend - time();
            $timeleft = convert_duration($t);

            $urgent = $t < $data['threshold'] ? " class=\"urgent\"" : "";
            $output .= "<tr$urgent><td class=\"left\">" . fullname($tempuser) . "</td><td class=\"right\">$timeleft</td>";
        }
        $output .= "</tr></table>";
        return $output;
    }

    private function make_message($data) {
        global $DB, $USER;

        $roleassignment = $DB->get_record('role_assignments', array(
            'userid' => $USER->id,
            'contextid' => $data['context']->id));

        $expire = $DB->get_record('local_temporary_enrolments', array('roleassignid' => $roleassignment->id));
        $t = $expire->timeend - time();
        $timeleft = convert_duration($t);

        $urgent = $t < $data['threshold'] ? " class=\"urgent\"" : "";
        $message = preg_replace("/\{TIMELEFT\}/", "<span>$timeleft</span>", $data['message']);
        return "<p$urgent>$message</p>";
    }

    public function content_is_trusted() {
        global $SCRIPT;

        if (!$context = context::instance_by_id($this->instance->parentcontextid)) {
            return false;
        }
        // Find out if this block is on the profile page.
        if ($context->contextlevel == CONTEXT_USER) {
            if ($SCRIPT === '/my/index.php') {
                // This is exception - page is completely private, nobody else may see content there.
                // That is why we allow JS here.
                return true;
            } else {
                // No JS on public personal pages, it would be a big security issue.
                return false;
            }
        }

        return true;
    }
}
