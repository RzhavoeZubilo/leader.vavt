@mod @mod_unilabel @unilabeltype_grid
Feature: Modify content of the unilabeltype grid

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
      | active  | 1   | unilabeltype_grid |
      | columns | 4   | unilabeltype_grid |
      | height  | 300 | unilabeltype_grid |

  @javascript @_file_upload
  Scenario: Add content to the unilabel as grid
    # Set up a unilabel.
    Given the following "activity" exists:
      | activity     | unilabel    |
      | course       | C1          |
      | idnumber     | mh1         |
      | name         | Testlabel   |
      | intro        | Hello label |
      | section      | 1           |
      | unilabeltype | grid        |

    When I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    # Edit the unilabel instance.
    And I should see "Edit content"
    And I click on "Edit content" "link" in the "#section-1" "css_element"
    # Open the grid section.
    And I click on "#id_unilabeltype_grid_hdr legend.ftoggler > a" "css_element"
    # Activate autorun.
    And I should see "Columns"
    And I set the field "Columns" to "4"
    # Select the value 100 from the height selection.
    And I should see "Height"
    And I click on "select#id_unilabeltype_grid_height" "css_element"
    And I click on "select#id_unilabeltype_grid_height option[value=\"100\"]" "css_element"
    # Set the Caption for the four tiles.
    # Both are defined by the css-id id_unilabeltype_grid_tilehdr_0 and ..._1.
    # Click on the toggle "Tile-1" and open the element
    And I click on "#id_unilabeltype_grid_tilehdr_0 legend.ftoggler > a" "css_element"
    And I should see "Title-1"
    And I set the field "Title-1" to "Title-Element-1"
    And I should see "Content-1"
    And I set the field "unilabeltype_grid_content[0][text]" to "Content-Element-1"
    And I upload "mod/unilabel/tests/fixtures/gradient-blue.png" file to "Image-1" filemanager
    # Click on the toggle "Tile-2" and open the element
    And I click on "#id_unilabeltype_grid_tilehdr_1 legend.ftoggler > a" "css_element"
    And I should see "Title-2"
    And I set the field "Title-2" to "Title-Element-2"
    And I should see "Content-2"
    And I set the field "unilabeltype_grid_content[1][text]" to "Content-Element-2"
    # Click on the toggle "Tile-3" and open the element
    And I click on "#id_unilabeltype_grid_tilehdr_2 legend.ftoggler > a" "css_element"
    And I should see "Title-3"
    And I set the field "Title-3" to "Title-Element-3"
    And I should see "Content-3"
    And I set the field "unilabeltype_grid_content[2][text]" to "Content-Element-3"
    # Click on the toggle "Tile-4" and open the element
    And I click on "#id_unilabeltype_grid_tilehdr_3 legend.ftoggler > a" "css_element"
    And I should see "Title-4"
    And I set the field "Title-4" to "Title-Element-4"
    And I should see "Content-4"
    And I set the field "unilabeltype_grid_content[3][text]" to "Content-Element-4"
    # Save the changes.
    And I press "Save changes"

    # See that the second slide is shown.
    Then I should see "Title-Element-1"
    And I should see "Title-Element-2"
    And I should see "Title-Element-3"
    And I should see "Title-Element-4"

  @javascript @_file_upload
  Scenario: Add more tiles to the content
    # Set up a unilabel.
    Given the following "activity" exists:
      | activity     | unilabel    |
      | course       | C1          |
      | idnumber     | mh1         |
      | name         | Testlabel   |
      | intro        | Hello label |
      | section      | 1           |
      | unilabeltype | grid        |

    When I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    # Edit the unilabel instance.
    And I should see "Edit content"
    And I click on "Edit content" "link" in the "#section-1" "css_element"
    # Open the grid section.
    And I click on "#id_unilabeltype_grid_hdr legend.ftoggler > a" "css_element"
    # Activate autorun.
    And I should see "Columns"
    And I set the field "Columns" to "4"
    # Select the value 100 from the height selection.
    And I should see "Height"
    And I click on "select#id_unilabeltype_grid_height" "css_element"
    And I click on "select#id_unilabeltype_grid_height option[value=\"100\"]" "css_element"
    # Set the Caption for the four tiles.
    # Both are defined by the css-id id_unilabeltype_grid_tilehdr_0 and ..._1.
    # Click on the toggle "Tile-1" and open the element
    And I click on "#id_unilabeltype_grid_tilehdr_0 legend.ftoggler > a" "css_element"
    And I should see "Title-1"
    And I set the field "Title-1" to "Title-Element-1"
    And I should see "Content-1"
    And I set the field "unilabeltype_grid_content[0][text]" to "Content-Element-1"
    And I upload "mod/unilabel/tests/fixtures/gradient-blue.png" file to "Image-1" filemanager
    # Click on the toggle "Tile-2" and open the element
    And I click on "#id_unilabeltype_grid_tilehdr_1 legend.ftoggler > a" "css_element"
    And I should see "Title-2"
    And I set the field "Title-2" to "Title-Element-2"
    And I should see "Content-2"
    And I set the field "unilabeltype_grid_content[1][text]" to "Content-Element-2"
    # Click on the toggle "Tile-3" and open the element
    And I click on "#id_unilabeltype_grid_tilehdr_2 legend.ftoggler > a" "css_element"
    And I should see "Title-3"
    And I set the field "Title-3" to "Title-Element-3"
    And I should see "Content-3"
    And I set the field "unilabeltype_grid_content[2][text]" to "Content-Element-3"
    # Click on the toggle "Tile-4" and open the element
    And I click on "#id_unilabeltype_grid_tilehdr_3 legend.ftoggler > a" "css_element"
    And I should see "Title-4"
    And I set the field "Title-4" to "Title-Element-4"
    And I should see "Content-4"
    And I set the field "unilabeltype_grid_content[3][text]" to "Content-Element-4"
    And I press "Add more tiles"

    Then I should see "Tile-5"
    And I should see "Tile-6"
    And I should see "Tile-7"
    And I should see "Tile-8"
