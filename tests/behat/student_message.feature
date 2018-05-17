@hampshire @block @block_temporary_enrolments @block_temporary_enrolments_student_message
Feature: The temporary_enrolments block student message (tells temporarily enrolled students how long they have left)

  # @javascript
  # Scenario: Testing the student message
  #   Given the following "courses" exist:
  #     | fullname    | shortname | numsections |
  #     | Test Course | test      | 15          |
  #   Given the following "users" exist:
  #     | username | firstname | lastname |
  #     | testone  | Test      | One      |
  #   Given the following "roles" exist:
  #     | name                 | shortname |
  #     | Temporarily Enrolled | temp      |
  #   Given I log in as "admin"
  #
  #   # Turn on the plugin
  #   Given I am on site homepage
  #   And I navigate to "Temporary enrolments" node in "Site administration>Plugins>Local plugins"
  #   And I click on "s__local_temporary_enrolments_onoff" "checkbox"
  #   And I select "temp" from the "s__local_temporary_enrolments_roleid" singleselect
  #   And I press "Save changes"
  #
  #   # Enrol test user in test course
  #   Given I am on site homepage
  #   And I follow "Test Course"
  #   And I follow "Participants"
  #   And I enrol "Test One" user as "Temporarily Enrolled"
  #   Given I am on site homepage
  #   And I follow "Test Course"
  #   And I turn editing mode on
  #   And I add the "Temporary enrolments" block
  #
  #   And I log out
  #   And I log in as "testone"
  #   And I am on site homepage
  #   When I follow "Test Course"
  #   Then I should see "Time remaining in temporary enrolment: 2" in the ".block_temporary_enrolments" "css_element"
  #   And I should see "weeks" in the ".block_temporary_enrolments" "css_element"

  @javascript
  Scenario: Testing the student message
    Given the following "courses" exist:
      | fullname    | shortname | numsections |
      | Test Course | test      | 15          |
    Given the following "users" exist:
      | username | firstname | lastname |
      | testone  | Test      | One      |
    Given the following "roles" exist:
      | name                 | shortname |
      | Temporarily Enrolled | temp      |
    Given I log in as "admin"

    # Turn on the plugin
    Given I am on site homepage
    And I navigate to "Temporary enrolments" node in "Site administration>Plugins>Local plugins"
    And I click on "s__local_temporary_enrolments_onoff" "checkbox"
    And I select "temp" from the "s__local_temporary_enrolments_roleid" singleselect
    And I set the field "s__local_temporary_enrolments_length[v]" to "1"
    And I set the field "s__local_temporary_enrolments_length[u]" to "days"
    And I press "Save changes"

    # Enrol test user in test course
    Given I am on site homepage
    And I follow "Test Course"
    And I follow "Participants"
    And I enrol "Test One" user as "Temporarily Enrolled"
    Given I am on site homepage
    And I follow "Test Course"
    And I turn editing mode on
    And I add the "Temporary enrolments" block

    And I log out
    And I log in as "testone"
    And I am on site homepage
    When I follow "Test Course"
    Then I should see "Time remaining in temporary enrolment:" in the ".block_temporary_enrolments" "css_element"
    Then I should see "1" in the ".block_temporary_enrolments" "css_element"
    Then I should see "day" in the ".block_temporary_enrolments" "css_element"
    And I should see "day" in the ".block_temporary_enrolments" "css_element"
    And the "class" attribute of ".block_temporary_enrolments p" "css_element" should contain "urgent"
