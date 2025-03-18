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
 * External functions for IGIS Flowise Bot
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_igisflowise_get_stats' => [
        'classname'   => 'local_igisflowise_external',
        'methodname'  => 'get_stats',
        'classpath'   => 'local/igisflowise/classes/external.php',
        'description' => 'Get IGIS Flowise Bot statistics',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/igisflowise:viewanalytics'
    ],
    'local_igisflowise_get_conversations' => [
        'classname'   => 'local_igisflowise_external',
        'methodname'  => 'get_conversations',
        'classpath'   => 'local/igisflowise/classes/external.php',
        'description' => 'Get IGIS Flowise Bot conversations',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/igisflowise:viewanalytics'
    ],
    'local_igisflowise_get_conversation_details' => [
        'classname'   => 'local_igisflowise_external',
        'methodname'  => 'get_conversation_details',
        'classpath'   => 'local/igisflowise/classes/external.php',
        'description' => 'Get IGIS Flowise Bot conversation details',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/igisflowise:viewanalytics'
    ],
    'local_igisflowise_delete_conversation' => [
        'classname'   => 'local_igisflowise_external',
        'methodname'  => 'delete_conversation',
        'classpath'   => 'local/igisflowise/classes/external.php',
        'description' => 'Delete IGIS Flowise Bot conversation',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities'=> 'local/igisflowise:managebot'
    ]
];
