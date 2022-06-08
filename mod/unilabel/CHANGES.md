## Release notes

### Version 2019020900

* respecting the new class "core_course_list_element"
* add setting options to activate or deactivate content types
* add setting options for carousel types to choose different carousel buttons
* add a litle javascript to ensure the start of the carousel

### Version 2019020901

* fix typo which stopped working the carousel buttons

### Version 2019030700

* Add option to define the columns for grid representation in course and topic teaser
* Change behaviour. If the unilabel shows a topic teaser of its own course it will ignore the topic
it is in it by its self.
* Clean the code to respect the moodle code guidelines

### Version 2019030701

* no features but optimized code

### Version 2019030702

* missing field "column" in backup for course and topic teaser
* optimize phpdoc comments

### Version 2019030703
* add capability check while defining course and topic teaser

### Version 2019050900
* set default capability mod/unilabel:edit for managers to allow
* add new option to define the carousel interval for course and topic teaser per instance
* fix small typos

### Version 2020022900
* fix output of images without img source in unilabletype_grid.
* add new options to define count of columns for normal, middle and small devices separately

### Version 2020061000
* fix referenced bootstrap javascript pointing to the new postion in Moodle 3.9

### Version 2020061001
* fix indentation bug

### Version 2020061002
* fix bug with modal started from another modal e.g. format_grid

### Version 2020061003
* fix bug with carousel nav buttons overlapping the nav drawer

### Version 2020110703
* moved fivecolumns css to styles.css
* small fixes in coding style

### Version 2021052300
* The internal name can now be edited.

### Version 2022012200
* All types using carousel have a new option to run the carousel automatically or not.

### Version 2022012201
* MBS-6088 (Stefan Hanauska): Fix behaviour for slides without images in type carousel and fix alt-attribute containing in type carousel

### Version 2022012202
* MBS-6151 (Stefan Hanuska): Make adding new slides consistent to grid
* Add phpunit test and behat acceptance test
* Add github actions as replacement for travis ci

### Version 2022030200
* Integrated a new type "Accordion" (Thanks to Stefan Hanuska)
* Add phpunit test and behat acceptance test to unilabeltype_accordion
