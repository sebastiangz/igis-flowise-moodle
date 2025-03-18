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
 * Extended admin settings for IGIS Flowise Bot
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Create analytics page only if conversations are being saved
$config = get_config('local_igisflowise');
if (!empty($config->save_conversations) && $config->save_conversations == 1) {
    // Register external page in admin tree
    $ADMIN->add('localplugins', new admin_externalpage(
        'localigisflowiseanalytics',
        get_string('analytics_title', 'local_igisflowise'),
        new moodle_url('/local/igisflowise/analytics.php'),
        'local/igisflowise:viewanalytics'
    ));
    
    // Add link to analytics page in settings page
    $settings->add(new local_igisflowise\admin_setting_externalpage(
        'local_igisflowise/analytics_link',
        get_string('analytics_title', 'local_igisflowise'),
        get_string('analytics_desc', 'local_igisflowise'),
        new moodle_url('/local/igisflowise/analytics.php')
    ));
}
