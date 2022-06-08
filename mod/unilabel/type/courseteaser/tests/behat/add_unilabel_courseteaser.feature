@mod @mod_unilabel @unilabeltype_courseteaser
Feature: Modify content of the unilabeltype courseteaser

  Background:
    Given the following "users" exist:
      | username | firstname | lastname |
      | teacher1 | Teacher   | 1        |
      | student1 | Student   | 1        |
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1        | topics |
      | Course 2 | C2        | topics |
      | Course 3 | C3        | topics |
      | Course 4 | C4        | topics |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | teacher1 | C2     | editingteacher |
      | teacher1 | C3     | editingteacher |
      | teacher1 | C4     | editingteacher |
    And the following config values are set as admin:
      | active           | 1        | unilabeltype_courseteaser |
      | autorun          | 1        | unilabeltype_courseteaser |
      | carouselinterval | 2        | unilabeltype_courseteaser |
      | columns          | 4        | unilabeltype_courseteaser |
      | presentation     | carousel | unilabeltype_courseteaser |
      | showintro        | 0        | unilabeltype_courseteaser |
    And the following "activity" exists:
      | activity | label          |
      | course   | C3             |
      | idnumber | label3         |
      | intro    | Hello course 3 |
      | section  | 1              |

  @javascript
  Scenario: Add courses to the unilabel as courseteaser
    # Set up a unilabel.
    Given the following "activity" exists:
      | activity     | unilabel     |
      | course       | C1           |
      | idnumber     | mh1          |
      | name         | Testlabel    |
      | intro        | Hello label  |
      | section      | 1            |
      | unilabeltype | courseteaser |

    When I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    # Edit the unilabel instance.
    And I should see "Edit content"
    And I click on "Edit content" "link" in the "#section-1" "css_element"
    # Fill in the title for the collapsed content and save the form.
    And I set the field "Courses" to "C2, C3, C4"
    And I press "Save changes"

    # The Courses should be shown.
    Then I should see "Course 2"
    # Now we wait until course 3 appears and click on it.
    And I wait "3" seconds
    And I click on "Course 3" "link" in the "#section-1 .unilabeltype_courseteaser.carousel" "css_element"
    # Now we should see the label from course 3
    And I should see "Hello course 3"
