<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Unit tests for generating instances.
 *
 * @package     unilabeltype_collapsedtext
 * @category    test
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace unilabeltype_collapsedtext;

/**
 * Unit tests for generating instances.
 *
 * @package     unilabeltype_collapsedtext
 * @category    test
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class generator_test extends \advanced_testcase {

    public function test_create_instance() {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();

        $this->assertFalse($DB->record_exists('unilabel', array('course' => $course->id)));
        $unilabel = $this->getDataGenerator()->create_module(
            'unilabel',
            array(
                'course'       => $course,
                'idnumber'     => 'mh1',
                'name'         => 'testlabel',
                'intro'        => 'Hello label',
                'unilabeltype' => 'collapsedtext',
            )
        );
        $records = $DB->get_records('unilabel', array('course' => $course->id, 'unilabeltype' => 'collapsedtext'), 'id');
        $this->assertEquals(1, count($records));
        $this->assertTrue(array_key_exists($unilabel->id, $records));

        $params = array(
            'course'       => $course->id,
            'idnumber'     => 'mh2',
            'name'         => 'testlabel2',
            'intro'        => 'Hello label-2',
            'unilabeltype' => 'collapsedtext',
        );
        $unilabel = $this->getDataGenerator()->create_module('unilabel', $params);
        $records = $DB->get_records('unilabel', array('course' => $course->id, 'unilabeltype' => 'collapsedtext'), 'id');
        $this->assertEquals(2, count($records));
        $this->assertEquals('testlabel2', $records[$unilabel->id]->name);
    }
}
