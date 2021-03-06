@block @block_temporary_enrolments @block_temporary_enrolments_admin_table
Feature: The temporary_enrolments block admin table (lists temporarily enrolled students in a course)

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
  Scenario: Testing the admin table
    And I log in as "admin"
    And I am on site homepage
    And I navigate to "Plugins > Local plugins > Temporary enrolments" in site administration
    And I click on "s_local_temporary_enrolments_onoff" "checkbox"
    And I select "temp" from the "s_local_temporary_enrolments_roleid" singleselect
    And I press "Save changes"
    And I am on site homepage
    And I follow "Test Course"
    And I follow "Participants"
    And I enrol "testone" user as "Temporarily Enrolled"
    When I am on site homepage
    And I follow "Test Course"
    And I turn editing mode on
    And I add the "Temporary enrolments" block
    Then I should see "Test One" in the ".block_temporary_enrolments" "css_element"
    And I should see "2" in the ".block_temporary_enrolments" "css_element"
    And I should see "weeks" in the ".block_temporary_enrolments" "css_element"

  @javascript
  Scenario: Testing the admin table times and urgent threshold
    And I log in as "admin"
    And I am on site homepage
    And I navigate to "Plugins > Local plugins > Temporary enrolments" in site administration
    And I click on "s_local_temporary_enrolments_onoff" "checkbox"
    And I select "temp" from the "s_local_temporary_enrolments_roleid" singleselect
    And I press "Save changes"
    And I am on site homepage
    And I follow "Test Course"
    And I follow "Participants"
    And I enrol "testone" user as "Temporarily Enrolled"
    When I am on site homepage
    And I follow "Test Course"
    And I turn editing mode on
    And I add the "Temporary enrolments" block
    Then I should see "Test One" in the ".block_temporary_enrolments" "css_element"
    And I should see "2" in the ".block_temporary_enrolments" "css_element"
    And I should see "weeks" in the ".block_temporary_enrolments" "css_element"
    Given I am on site homepage
    And I navigate to "Plugins > Local plugins > Temporary enrolments" in site administration
    And I set the field "s_local_temporary_enrolments_length[v]" to "13"
    And I set the field "s_local_temporary_enrolments_length[u]" to "days"
    And I press "Save changes"
    And I am on site homepage
    When I follow "Test Course"
    Then I should see "1" in the ".block_temporary_enrolments" "css_element"
    And I should see "week" in the ".block_temporary_enrolments" "css_element"
    And I should see "6" in the ".block_temporary_enrolments" "css_element"
    And I should see "days" in the ".block_temporary_enrolments" "css_element"
    Given I am on site homepage
    And I navigate to "Plugins > Local plugins > Temporary enrolments" in site administration
    And I set the field "s_local_temporary_enrolments_length[v]" to "8"
    And I set the field "s_local_temporary_enrolments_length[u]" to "days"
    And I press "Save changes"
    And I am on site homepage
    When I follow "Test Course"
    Then I should see "1" in the ".block_temporary_enrolments" "css_element"
    And I should see "week" in the ".block_temporary_enrolments" "css_element"
    And I should see "1" in the ".block_temporary_enrolments" "css_element"
    And I should see "day" in the ".block_temporary_enrolments" "css_element"
    Given I am on site homepage
    And I navigate to "Plugins > Local plugins > Temporary enrolments" in site administration
    And I set the field "s_local_temporary_enrolments_length[v]" to "2"
    And I set the field "s_local_temporary_enrolments_length[u]" to "days"
    And I press "Save changes"
    And I am on site homepage
    When I follow "Test Course"
    Then I should see "2" in the ".block_temporary_enrolments" "css_element"
    And I should see "days" in the ".block_temporary_enrolments" "css_element"
    And the "class" attribute of "//table[@class='block_temporary_enrolments_table']//tr[position()=2]" "xpath_element" should contain "urgent"
