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
 * Unit tests for general unilabel features.
 *
 * @package     mod_unilabel
 * @category    test
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_unilabel;

/**
 * Unit tests for general unilabel features.
 *
 * @package     mod_unilabel
 * @category    test
 * @author      Andreas Grabs <info@grabs-edv.de>
 * @copyright   2018 onwards Grabs EDV {@link https://www.grabs-edv.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class lib_test extends \advanced_testcase {

    public function test_get_only_active_unilabeltypes() {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();

        // Prepare the settings and activate all unilabeltypes.
        // The unilabeltype "simpletext" is an exception. This type always is active.
        $plugins = \core_component::get_plugin_list('unilabeltype');
        $countallplugins = count($plugins);
        foreach ($plugins as $name => $notused) {
            if ($name == 'simpletext') {
                continue;
            }
            set_config('active', true, 'unilabeltype_'.$name);
        }

        $unilabeltypes = \mod_unilabel\factory::get_plugin_list();
        $countactiveplugins = count($unilabeltypes);

        // Do we have found plugins at all?
        $this->assertTrue(count($unilabeltypes) > 0, 'no unilabeltypes found');
        // Do we have found all plugins?
        $this->assertTrue($countactiveplugins == $countallplugins);

        if (empty($unilabeltypes)) {
            return;
        }

        foreach ($unilabeltypes as $unilabeltype => $typestring) {
            $typeinstance = \mod_unilabel\factory::get_plugin($unilabeltype);
            $this->assertNotEmpty($typeinstance);
            if (empty($typeinstance)) {
                continue;
            }
            $this->assertTrue($typestring == $typeinstance->get_name());
            $this->assertTrue($typeinstance->is_active());
        }

        // Now we set all plugins inactive.
        foreach ($plugins as $name => $notused) {
            if ($name == 'simpletext') {
                continue;
            }
            set_config('active', false, 'unilabeltype_'.$name);
        }

        $unilabeltypes = \mod_unilabel\factory::get_plugin_list();
        $countactiveplugins = count($unilabeltypes);
        $this->assertEquals(1, $countactiveplugins);
    }
}
