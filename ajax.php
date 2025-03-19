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
 * AJAX handler for IGIS Flowise Bot
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/igisflowise/lib.php');

// Make sure the session is properly set up
require_sesskey();

// Get the action parameter.
$action = required_param('action', PARAM_ALPHANUMEXT);

// Get config.
$config = get_config('local_igisflowise');

// Check if conversation saving is enabled.
if (empty($config->save_conversations) || $config->save_conversations != 1) {
    echo json_encode(['success' => false, 'message' => 'Conversation saving is disabled']);
    die;
}

// Handle different actions.
switch ($action) {
    case 'log_conversation':
        log_conversation();
        break;
    case 'log_message':
        log_message();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        die;
}

/**
 * Log a conversation start or update.
 */
function log_conversation() {
    global $DB, $USER;
    
    $sessionid = required_param('session_id', PARAM_ALPHANUMEXT);
    $status = optional_param('status', 'active', PARAM_ALPHA);
    
    // Check if the conversation exists.
    $existing = $DB->get_record('local_igisflowise_convs', ['sessionid' => $sessionid]);
    
    $now = time();
    
    if ($existing) {
        // Update existing conversation.
        $existing->status = $status;
        $existing->timemodified = $now;
        $DB->update_record('local_igisflowise_convs', $existing);
        $conversationid = $existing->id;
    } else {
        // Create new conversation.
        $conversation = new stdClass();
        $conversation->userid = isloggedin() && !isguestuser() ? $USER->id : null;
        $conversation->sessionid = $sessionid;
        $conversation->status = $status;
        $conversation->timecreated = $now;
        $conversation->timemodified = null;
        
        $conversationid = $DB->insert_record('local_igisflowise_convs', $conversation);
    }
    
    // Log analytics event.
    log_analytics_event('conversation_' . ($existing ? 'continued' : 'started'), [
        'conversation_id' => $conversationid,
        'session_id' => $sessionid
    ]);
    
    echo json_encode(['success' => true, 'data' => ['conversation_id' => $conversationid]]);
    die;
}

/**
 * Log a message.
 */
function log_message() {
    global $DB;
    
    $conversationid = required_param('conversation_id', PARAM_INT);
    $message = required_param('message', PARAM_TEXT);
    $type = required_param('type', PARAM_ALPHA);
    
    // Validate required fields.
    if (empty($conversationid) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        die;
    }
    
    // Validate conversation exists.
    if (!$DB->record_exists('local_igisflowise_convs', ['id' => $conversationid])) {
        echo json_encode(['success' => false, 'message' => 'Invalid conversation ID']);
        die;
    }
    
    // Insert message.
    $messagerecord = new stdClass();
    $messagerecord->conversationid = $conversationid;
    $messagerecord->message = $message;
    $messagerecord->type = $type;
    $messagerecord->timecreated = time();
    
    $messageid = $DB->insert_record('local_igisflowise_messages', $messagerecord);
    
    // Log analytics event.
    log_analytics_event('message_' . $type, [
        'conversation_id' => $conversationid,
        'message_length' => core_text::strlen($message)
    ]);
    
    echo json_encode(['success' => true, 'data' => ['message_id' => $messageid]]);
    die;
}

/**
 * Log an analytics event.
 *
 * @param string $eventtype The type of event.
 * @param array $data Additional event data.
 */
function log_analytics_event($eventtype, $data = []) {
    global $DB;
    
    $event = new stdClass();
    $event->eventtype = $eventtype;
    $event->eventdata = json_encode($data);
    $event->timecreated = time();
    
    $DB->insert_record('local_igisflowise_analytics', $event);
    
    // Opcional: Enviar el evento a un webhook si está configurado
    $config = get_config('local_igisflowise');
    if (!empty($config->webhook_url) && !empty($config->webhook_events)) {
        $webhook_events = explode(',', $config->webhook_events);
        if (in_array($eventtype, $webhook_events)) {
            // Implementación del envío al webhook
        }
    }
}