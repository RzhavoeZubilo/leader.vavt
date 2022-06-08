@mod @mod_unilabel
Feature: Show teachers and users the unilabel on the course page
  In order to edit the unilabel content
  As a teacher
  I need to see the button "Edit content"
  As a student
  I do not see the button "Edit content"

  Background:
    Given the following "users" exist:
      | username | firstname | lastname |
      | teacher1 | Teacher   | 1        |
      | student1 | Student   | 1        |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  @javascript
  Scenario: See the "Edit content" button as teacher
    # Set up a unilabel.
    When I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "Unilabel" to section "1" and I fill the form with:
      | Name          | Testlabel1  |
      | Unilabel text | Hello label |
      | Label type    | Simple text |

    # Should See the unilabel and the button
    Then I should see "Hello label"
    And I should see "Edit content"

  @javascript
  Scenario: Do not see the "Edit content" button as student
    When I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "Unilabel" to section "1" and I fill the form with:
      | Name          | Testlabel1  |
      | Unilabel text | Hello label |
      | Label type    | Simple text |
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage

    # Should See the unilabel and the button
    Then I should see "Hello label"
    And I should not see "Edit content"
