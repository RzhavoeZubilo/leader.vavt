@mod @mod_unilabel @unilabeltype_topicteaser
Feature: Modify content of the unilabeltype topicteaser

  Background:
    Given the following "users" exist:
      | username | firstname | lastname |
      | teacher1 | Teacher   | 1        |
      | student1 | Student   | 1        |
    And the following "courses" exist:
      | fullname | shortname | format | coursedisplay | numsections |
      | Course 1 | C1        | topics | 0             | 4           |
      | Course 2 | C2        | topics | 0             | 4           |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | teacher1 | C2     | editingteacher |
    And the following config values are set as admin:
      | active           | 1          | unilabeltype_topicteaser |
      | autorun          | 1          | unilabeltype_topicteaser |
      | carouselinterval | 2          | unilabeltype_topicteaser |
      | columns          | 4          | unilabeltype_topicteaser |
      | presentation     | carousel   | unilabeltype_topicteaser |
      | clickaction      | opendialog | unilabeltype_topicteaser |
      | showintro        | 0          | unilabeltype_topicteaser |
      | showcoursetitle  | 1          | unilabeltype_topicteaser |
    And the following "activity" exists:
      | activity | label         |
      | course   | C2            |
      | idnumber | label1        |
      | intro    | Hello topic 1 |
      | section  | 1             |
    And the following "activity" exists:
      | activity | label         |
      | course   | C2            |
      | idnumber | label2        |
      | intro    | Hello topic 2 |
      | section  | 2             |
    And the following "activity" exists:
      | activity | label         |
      | course   | C2            |
      | idnumber | label3        |
      | intro    | Hello topic 3 |
      | section  | 3             |
    And the following "activity" exists:
      | activity | label         |
      | course   | C2            |
      | idnumber | label4        |
      | intro    | Hello topic 4 |
      | section  | 4             |

  @javascript
  Scenario: Add a course to unilabel as topicteaser
    # Set up a unilabel.
    Given the following "activity" exists:
      | activity     | unilabel    |
      | course       | C1          |
      | idnumber     | mh1         |
      | name         | Testlabel   |
      | intro        | Hello label |
      | section      | 1           |
      | unilabeltype | topicteaser |

    When I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    # Edit the unilabel instance.
    And I should see "Edit content"
    And I click on "Edit content" "link" in the "#section-1" "css_element"
    # Fill in the title for the collapsed content and save the form.
    And I set the field "Course" to "C2"
    And I press "Save changes"

    # The Course name from course 2 should be shown.
    Then I should see "Course 2"
    # Now we wait until topic 2 appears and click on it.
    And I wait "3" seconds
    # And I click on "Topic 2" "link" in the "#section-1 .unilabeltype_topicteaser.carousel" "css_element"
    And I click on "#section-1 .unilabeltype_topicteaser.carousel .carousel-inner .carousel-item.active > a" "css_element"
    # Now we should see the label from topic 2 as modal box
    And I should see "Hello topic 2"
