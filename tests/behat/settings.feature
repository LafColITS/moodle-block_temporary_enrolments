@block @block_temporary_enrolments @block_temporary_enrolments_settings
Feature: The temporary_enrolments block settings (both instance and admin level)

  Background:
    Given the following "courses" exist:
      | fullname    | shortname |
      | Test Course | test      |
    Given the following "users" exist:
      | username | firstname | lastname |
      | testone  | Test      | One      |
    Given the following "roles" exist:
      | name                 | shortname |
      | Temporarily Enrolled | temp      |

  @javascript
  Scenario: Admin level settings display correctly, have proper defaults, and correctly provide defaults for and/or override instance level settings
    Given I log in as "admin"
    And I am on site homepage
    And I navigate to "Plugins > Local plugins > Temporary enrolments" in site administration
    And I click on "s_local_temporary_enrolments_onoff" "checkbox"
    And I set the field "s_local_temporary_enrolments_length[v]" to "2"
    And I set the field "s_local_temporary_enrolments_length[u]" to "days"
    And I select "temp" from the "s_local_temporary_enrolments_roleid" singleselect
    And I press "Save changes"
    And I am on site homepage
    When I navigate to "Plugins > Blocks > Temporary enrolments" in site administration
    Then I should see "Urgent threshold"
    And I should see "Student display message"
    And the field "s__block_temporary_enrolments_urgent_threshold" matches value "3 days"
    And the field "s__block_temporary_enrolments_student_message" matches value "Time remaining in temporary enrolment: {TIMELEFT}"
    And I set the field "s__block_temporary_enrolments_urgent_threshold" to "1 day"
    And I set the field "s__block_temporary_enrolments_student_message" to "Time left: {TIMELEFT}"
    And I press "Save changes"
    And I am on site homepage
    And I follow "Test Course"
    And I turn editing mode on
    And I add the "Temporary enrolments" block
    And I follow "Participants"
    And I enrol "testone" user as "Temporarily Enrolled"
    And I log out
    And I log in as "testone"
    And I am on site homepage
    And I follow "Test Course"
    And I should see "Time left:" in the ".block_temporary_enrolments" "css_element"
    And I should see "2" in the ".block_temporary_enrolments" "css_element"
    And I should see "days" in the ".block_temporary_enrolments" "css_element"
    Then ".block_temporary_enrolments p" "css_element" should exist
    And ".block_temporary_enrolments p.urgent" "css_element" should not exist
