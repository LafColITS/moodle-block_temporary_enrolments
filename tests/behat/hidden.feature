@block @block_temporary_enrolments @block_temporary_enrolments_hidden
Feature: The temporary enrolments block is hidden when there are no temporary users, if the user is not a temporary user or an admin, and if the plugin is turned off.

  Background:
    Given the following "courses" exist:
      | fullname    | shortname |
      | Test Course | test      |
    Given the following "users" exist:
      | username | firstname | lastname |
      | testone  | Test      | One      |
      | testtwo  | Test      | Two      |
    Given the following "roles" exist:
      | name                 | shortname |
      | Temporarily Enrolled | temp      |

  @javascript
  Scenario: Testing hidden for regular students
    Given I log in as "admin"
    And I am on site homepage
    And I navigate to "Plugins > Local plugins > Temporary enrolments" in site administration
    And I click on "s_local_temporary_enrolments_onoff" "checkbox"
    And I select "temp" from the "s_local_temporary_enrolments_roleid" singleselect
    And I press "Save changes"
    And I am on site homepage
    And I follow "Test Course"
    And I follow "Participants"
    And I enrol "Test One" user as "Student"
    And I enrol "Test Two" user as "Temporarily Enrolled"
    Given I am on site homepage
    And I follow "Test Course"
    And I turn editing mode on
    And I add the "Temporary enrolments" block
    When I turn editing mode off
    Then I should see "Test Two"
    And I log out
    And I log in as "testone"
    And I am on site homepage
    When I follow "Test Course"
    Then I should not see "Temporary enrolment"
    And I should not see "Test Two"

  @javascript
  Scenario: Testing hidden b/c plugin turned off
    Given I log in as "admin"
    And I am on site homepage
    And I navigate to "Plugins > Local plugins > Temporary enrolments" in site administration
    And I select "temp" from the "s_local_temporary_enrolments_roleid" singleselect
    And I press "Save changes"
    And I am on site homepage
    And I follow "Test Course"
    And I wait until the page is ready
    And I follow "Participants"
    And I enrol "Test One" user as "Temporarily Enrolled"
    And I am on site homepage
    And I follow "Test Course"
    And I turn editing mode on
    And I add the "Temporary enrolments" block
    When I turn editing mode off
    Then I should not see "Temporary enrolment"
    And I should not see "Test One"

  @javascript
  Scenario: Testing hidden (no content) for admins
    Given I log in as "admin"
    And I am on site homepage
    And I navigate to "Plugins > Local plugins > Temporary enrolments" in site administration
    And I click on "s_local_temporary_enrolments_onoff" "checkbox"
    And I select "temp" from the "s_local_temporary_enrolments_roleid" singleselect
    And I press "Save changes"
    And I am on site homepage
    And I follow "Test Course"
    And I turn editing mode on
    And I add the "Temporary enrolments" block
    When I turn editing mode off
    Then I should not see "Temporary enrolment"

  @javascript
  Scenario: Testing hidden (no content) for regular (non-temp) users
    Given I log in as "admin"
    And I am on site homepage
    And I navigate to "Plugins > Local plugins > Temporary enrolments" in site administration
    And I click on "s_local_temporary_enrolments_onoff" "checkbox"
    And I select "temp" from the "s_local_temporary_enrolments_roleid" singleselect
    And I press "Save changes"
    And I am on site homepage
    And I follow "Test Course"
    And I follow "Participants"
    And I enrol "Test One" user as "Student"
    And I am on site homepage
    And I follow "Test Course"
    And I turn editing mode on
    And I add the "Temporary enrolments" block
    When I turn editing mode off
    And I log out
    And I log in as "testone"
    And I am on site homepage
    And I follow "Test Course"
    Then I should not see "Temporary enrolment"
