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
 * Admin page definition for IGIS Flowise Bot
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_igisflowise;

defined('MOODLE_INTERNAL') || die();

/**
 * Class to create analytics page in admin settings
 */
class admin_setting_externalpage extends \admin_setting {
    
    /** @var string The URL of the external page */
    protected $url;
    
    /** @var string The capability required to access the page */
    protected $capability;
    
    /**
     * Constructor
     *
     * @param string $name Unique ascii name, either 'mysetting' for settings or 'myplugin/mysetting' for plugins
     * @param string $visiblename Localised name
     * @param string $description Localised description
     * @param string $url The URL of the external page
     * @param string $capability The capability required to access the page (optional)
     */
    public function __construct($name, $visiblename, $description, $url, $capability = '') {
        parent::__construct($name, $visiblename, $description, '');
        $this->url = $url;
        $this->capability = $capability;
    }
    
    /**
     * Always returns true, because no setting can be changed on this page
     *
     * @return bool Always returns true
     */
    public function get_setting() {
        return true;
    }
    
    /**
     * Always returns true, because no setting can be changed on this page
     *
     * @return bool Always returns true
     */
    public function get_defaultsetting() {
        return true;
    }
    
    /**
     * Never write settings
     *
     * @param string $data Unused
     * @return string Always returns empty string
     */
    public function write_setting($data) {
        // Do not write any settings
        return '';
    }
    
    /**
     * Returns an HTML link to the external page
     *
     * @param string $data Unused
     * @param string $query Unused
     * @return string HTML link to the external page
     */
    public function output_html($data, $query = '') {
        global $OUTPUT;
        
        $context = [
            'url' => $this->url,
            'title' => $this->visiblename,
            'description' => $this->description
        ];
        
        return $OUTPUT->render_from_template('local_igisflowise/admin_setting_externalpage', $context);
    }
}
