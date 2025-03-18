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

// Get the action parameter.
$action = required_param('action', PARAM_ALPHANUMEXT);

// Check session key for all actions.
require_sesskey();

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
    case 'get_conversations':
        require_capability('local/igisflowise:viewanalytics', context_system::instance());
        get_conversations();
        break;
    case 'get_conversation_details':
        require_capability('local/igisflowise:viewanalytics', context_system::instance());
        get_conversation_details();
        break;
    case 'delete_conversation':
        require_capability('local/igisflowise:managebot', context_system::instance());
        delete_conversation();
        break;
    case 'get_stats':
        require_capability('local/igisflowise:viewanalytics', context_system::instance());
        get_stats();
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
    
    echo json_encode(['success' => true, 'conversation_id' => $conversationid]);
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
    
    echo json_encode(['success' => true, 'message_id' => $messageid]);
    die;
}

/**
 * Get a list of conversations.
 */
function get_conversations() {
    global $DB;
    
    $datefilter = optional_param('date_filter', 'all', PARAM_ALPHA);
    $page = optional_param('page', 1, PARAM_INT);
    $perpage = 20;
    
    // Build where clause based on date filter.
    $where = '';
    $params = [];
    
    switch ($datefilter) {
        case 'today':
            $where = "WHERE FROM_UNIXTIME(c.timecreated, '%Y-%m-%d') = CURDATE()";
            break;
        case 'yesterday':
            $where = "WHERE FROM_UNIXTIME(c.timecreated, '%Y-%m-%d') = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            break;
        case 'last_week':
            $where = "WHERE c.timecreated >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 7 DAY))";
            break;
        case 'last_month':
            $where = "WHERE c.timecreated >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY))";
            break;
    }
    
    // Calculate offset.
    $offset = ($page - 1) * $perpage;
    
    // Get total count.
    $countsql = "SELECT COUNT(*) FROM {local_igisflowise_convs} c $where";
    $total = $DB->count_records_sql($countsql, $params);
    
    // Get conversations.
    $sql = "SELECT c.*, 
            (SELECT COUNT(*) FROM {local_igisflowise_messages} m WHERE m.conversationid = c.id) as message_count,
            (SELECT username FROM {user} u WHERE u.id = c.userid) as username
            FROM {local_igisflowise_convs} c
            $where
            ORDER BY c.timecreated DESC
            LIMIT $perpage OFFSET $offset";
    
    $conversations = $DB->get_records_sql($sql, $params);
    
    // Format data for output.
    $data = [];
    foreach ($conversations as $conversation) {
        $data[] = [
            'id' => $conversation->id,
            'username' => $conversation->username ?: get_string('anonymous', 'local_igisflowise'),
            'status' => $conversation->status,
            'started_at' => userdate($conversation->timecreated),
            'ended_at' => $conversation->timemodified ? userdate($conversation->timemodified) : null,
            'message_count' => $conversation->message_count
        ];
    }
    
    echo json_encode([
        'success' => true, 
        'conversations' => $data,
        'total' => $total,
        'pages' => ceil($total / $perpage)
    ]);
    die;
}

/**
 * Get details of a specific conversation.
 */
function get_conversation_details() {
    global $DB;
    
    $conversationid = required_param('conversation_id', PARAM_INT);
    
    // Get conversation details.
    $conversation = $DB->get_record('local_igisflowise_convs', ['id' => $conversationid]);
    if (!$conversation) {
        echo json_encode(['success' => false, 'message' => 'Conversation not found']);
        die;
    }
    
    // Get user details if applicable.
    if ($conversation->userid) {
        $user = $DB->get_record('user', ['id' => $conversation->userid], 'id, username, firstname, lastname');
        $conversation->username = fullname($user);
    } else {
        $conversation->username = get_string('anonymous', 'local_igisflowise');
    }
    
    // Get messages.
    $messages = $DB->get_records('local_igisflowise_messages', 
                                ['conversationid' => $conversationid], 
                                'timecreated ASC');
    
    // Format messages.
    $messagedata = [];
    foreach ($messages as $message) {
        $messagedata[] = [
            'id' => $message->id,
            'message' => $message->message,
            'type' => $message->type,
            'timestamp' => userdate($message->timecreated)
        ];
    }
    
    // Format conversation.
    $conversationdata = [
        'id' => $conversation->id,
        'username' => $conversation->username,
        'status' => $conversation->status,
        'started_at' => userdate($conversation->timecreated),
        'ended_at' => $conversation->timemodified ? userdate($conversation->timemodified) : null
    ];
    
    echo json_encode([
        'success' => true,
        'conversation' => $conversationdata,
        'messages' => $messagedata
    ]);
    die;
}

/**
 * Delete a conversation.
 */
function delete_conversation() {
    global $DB;
    
    $conversationid = required_param('conversation_id', PARAM_INT);
    
    // Check if the conversation exists.
    if (!$DB->record_exists('local_igisflowise_convs', ['id' => $conversationid])) {
        echo json_encode(['success' => false, 'message' => 'Conversation not found']);
        die;
    }
    
    // Delete messages first (though foreign keys should handle this).
    $DB->delete_records('local_igisflowise_messages', ['conversationid' => $conversationid]);
    
    // Delete conversation.
    $DB->delete_records('local_igisflowise_convs', ['id' => $conversationid]);
    
    echo json_encode(['success' => true]);
    die;
}

/**
 * Get statistics data.
 */
function get_stats() {
    global $DB;
    
    // Get total conversations.
    $totalconversations = $DB->count_records('local_igisflowise_convs');
    
    // Get total messages.
    $totalmessages = $DB->count_records('local_igisflowise_messages');
    
    // Calculate response rate.
    $completedconversations = $DB->count_records('local_igisflowise_convs', ['status' => 'completed']);
    $responserate = $totalconversations > 0 ? round(($completedconversations / $totalconversations) * 100, 2) : 0;
    
    // Get conversations per day (for chart).
    $conversationschart = get_conversations_chart_data();
    
    // Get messages per day (for chart).
    $messageschart = get_messages_chart_data();
    
    echo json_encode([
        'success' => true,
        'total_conversations' => $totalconversations,
        'total_messages' => $totalmessages,
        'response_rate' => $responserate,
        'conversations_chart' => $conversationschart,
        'messages_chart' => $messageschart
    ]);
    die;
}

/**
 * Get data for conversations chart.
 *
 * @return array Array of date => count pairs for the last 30 days.
 */
function get_conversations_chart_data() {
    global $DB;
    
    $data = [];
    for ($i = 29; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $starttime = strtotime($date . ' 00:00:00');
        $endtime = strtotime($date . ' 23:59:59');
        
        $count = $DB->count_records_select('local_igisflowise_convs', 
                                        "timecreated >= ? AND timecreated <= ?", 
                                        [$starttime, $endtime]);
        
        $data[] = [
            'date' => $date,
            'count' => (int)$count
        ];
    }
    
    return $data;
}

/**
 * Get data for messages chart.
 *
 * @return array Array of date => user/bot counts for the last 30 days.
 */
function get_messages_chart_data() {
    global $DB;
    
    $data = [];
    for ($i = 29; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $starttime = strtotime($date . ' 00:00:00');
        $endtime = strtotime($date . ' 23:59:59');
        
        $usercount = $DB->count_records_select('local_igisflowise_messages',
                                            "timecreated >= ? AND timecreated <= ? AND type = ?",
                                            [$starttime, $endtime, 'user']);
        
        $botcount = $DB->count_records_select('local_igisflowise_messages',
                                            "timecreated >= ? AND timecreated <= ? AND type = ?",
                                            [$starttime, $endtime, 'bot']);
        
        $data[] = [
            'date' => $date,
            'user' => (int)$usercount,
            'bot' => (int)$botcount
        ];
    }
    
    return $data;
}

/**
 * Log an analytics event.
 *
 * @param string $eventtype The type of event.
 * @param array $data Additional event data.
 */
function log_analytics_event($eventtype, $data = []) {
    global $DB;
    
    $config = get_config('local_igisflowise');
    
    // Only log if analytics is enabled.
    if (empty($config->save_conversations) || $config->save_conversations != 1) {
        return;
    }
    
    $event = new stdClass();
    $event->eventtype = $eventtype;
    $event->eventdata = json_encode($data);
    $event->timecreated = time();
    
    $DB->insert_record('local_igisflowise_analytics', $event);
}
