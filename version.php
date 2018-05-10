<?php
/**
 * Displays users who are temporarily enrolled (as defined by the Temporary Enrolments plugin).
 *
 * @package    block_temporary_enrolments
 * @copyright  2018 onwards Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$plugin->version = 2018051000;
$plugin->requires = 2017111300;
$plugin->component = 'block_temporary_enrolments';
$plugin->maturity = MATURITY_ALPHA;
$plugin->release = '1.0.0';
$plugin->dependencies = array(
  'local_temporary_enrolments' => 2017060800
);
