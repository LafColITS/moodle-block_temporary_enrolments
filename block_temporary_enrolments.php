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
        global $CFG, $DB, $COURSE, $OUTPUT, $USER;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        $filteropt = new stdClass;
        $filteropt->overflowdiv = true;
        if ($this->content_is_trusted()) {
            // Fancy html allowed only on course, category and system blocks.
            $filteropt->noclean = true;
        }

        // if ($CFG->block_temporary_enrolments_urgent_threshold_override && isset($this->config->urgent_threshold)) {
        //     $this->config->urgent_threshold = $CFG->block_temporary_enrolments_urgent_threshold;
        // }
        //
        // if ($CFG->block_temporary_enrolments_student_message_override && isset($this->config->urgent_threshold)) {
        //     $this->config->student_message = $CFG->block_temporary_enrolments_student_message;
        // }

        // Default threshold to global setting.
        $threshold = ($CFG->block_temporary_enrolments_urgent_threshold) * 86400;
        if (isset($this->config->urgent_threshold)) {
            // If instance config is set, use that instead.
            $threshold = $this->config->urgent_threshold;
            $threshold = explode(" ", $threshold);
            $threshold = ($threshold[0]) * 86400;
        }

        $message = $CFG->block_temporary_enrolments_student_message;
        if (! empty($this->config->student_message)) {
            $message = $this->config->student_message;
        }

        $this->content = new stdClass();
        $this->content->footer = '';

        $context = context_course::instance($COURSE->id);
        $role = get_temp_role();
        // if (!$role = $DB->get_record('role', array('shortname' => 'temporary_enrollment'))) {
        //     print_error('missingtemprole');
        // }

        if (has_capability('block/temporary_enrolments:canviewall', $context)) {
            $tempusers = get_role_users($role->id, $context, true);
            $this->content->text = "<table class=\"block_temporary_enrolments_table\">"
                        . "<tr>"
                        . "<th>Student</th>"
                        . "<th>Time Remaining</th>"
                        . "</tr>";
            if (count($tempusers) == 0) {
                $this->content = 0;
                return;
            }
            foreach ($tempusers as $tempuser) {
                $roleassignment = $DB->get_record('role_assignments', array('userid' => $tempuser->id, 'contextid' => $context->id));
                $expire = $DB->get_record('local_temporary_enrolments', array('roleassignid' => $roleassignment->id));
                $t = $expire->timeend - time();
                $timeleft = convert_duration($t);
                $urgent = $t < $threshold ? " class=\"urgent\"" : "";
                $this->content->text .= "<tr$urgent><td class=\"left\">" . fullname($tempuser) . "</td><td class=\"right\">$timeleft</td>";
            }
            $this->content->text .= "</tr></table>";
        } else if ($usercourserole = $DB->get_record('role_assignments', array('userid' => $USER->id, 'contextid' => $context->id))) {
            if ($usercourserole->roleid == $role->id) {
                $expire = $DB->get_record('local_temporary_enrolments', array('roleassignid' => $usercourserole->id));
                $t = $expire->timeend - time();
                $timeleft = convert_duration($t);
                $urgent = $t < $threshold ? " class=\"urgent\"" : "";
                $message = preg_replace("/\{TIMELEFT\}/", "<span>$timeleft</span>", $message);
                $this->content->text = "<p$urgent>$message</p>";
            }
        }

        unset($filteropt); // Memory footprint.

        return $this->content;
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
