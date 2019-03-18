# Temporary Enrolments Block

## Prerequisites

This block requires the [Local Temporary Enrolments](https://moodle.org/plugins/local_temporary_enrolments) plugin to function.

## Use

Add this block to any course

* For any user who is temporarily enrolled in the course (according to Local Temporary Enrolments), it will display the status of their enrolment and the time left until it expires.
* For teachers/administrators, it will display a table listing students who are temporarily enrolled and their time remaining.

## Settings

You can configure:

1. The 'urgency threshold' for temporary enrolment display. 'Urgent' temporary enrolments will be styled to be more noticeable. The setting specifies how close to the expiration date an enrolment must be to count as urgent.

2. The message displayed to students. Use the {TIMELEFT} flag to display the student's remaining enrolment time.

## Versions

This plugin is maintained with separate releases and branches for each major Moodle version. Currently there are versions available for 3.3, 3.4, 3.5, and 3.6. This is the version for __Moodle 3.4__.

Releases are tagged based on the following pattern:
[major].[minor].[patch]-[Moodle major version].[Moodle version specific patch]

For example, v1.5.7-35.1 would be plugin vresion 1.5.7 for Moodle 35, and includes one Moodle 35 specific patch for this plugin version. It would have corresponding versions v1.5.7-34.X and v1.5.7-33.X.