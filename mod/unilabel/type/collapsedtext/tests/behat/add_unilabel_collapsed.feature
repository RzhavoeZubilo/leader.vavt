@mod @mod_unilabel @unilabeltype_collapsedtext
Feature: Modify content of the unilabeltype collapsedtext

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
    And the following config values are set as admin:
      | active | 1 | unilabeltype_collapsedtext |

  @javascript
  Scenario: Add a title for the collapsed text
    # Set up a unilabel.
    Given the following "activity" exists:
      | activity     | unilabel      |
      | course       | C1            |
      | idnumber     | mh1           |
      | name         | Testlabel     |
      | intro        | Hello label   |
      | section      | 1             |
      | unilabeltype | collapsedtext |

    When I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    # Edit the unilabel instance.
    And I should see "Edit content"
    And I click on "Edit content" "link" in the "#section-1" "css_element"
    # Fill in the title for the collapsed content and save the form.
    And I set the field "id_unilabeltype_collapsedtext_title" to "Click here"
    And I press "Save changes"

    # The "Click here" link should be shown.
    Then I should see "Click here"
    # After click on "Click here" the content should be shown.
    And I click on "#section-1 .unilabel-content .unilabeltype-collapsed a.unilabel-title.collapsed" "css_element"
    And I should see "Hello label"
