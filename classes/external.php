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
 * External API for IGIS Flowise Bot
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");

/**
 * IGIS Flowise Bot external functions
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_igisflowise_external extends external_api {

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function get_stats_parameters() {
        return new external_function_parameters([]);
    }

    /**
     * Get statistics data
     *
     * @return array
     */
    public static function get_stats() {
        global $DB;
        
        // Check capability
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('local/igisflowise:viewanalytics', $context);
        
        // Get total conversations
        $totalconversations = $DB->count_records('local_igisflowise_convs');
        
        // Get total messages
        $totalmessages = $DB->count_records('local_igisflowise_messages');
        
        // Calculate response rate
        $completedconversations = $DB->count_records('local_igisflowise_convs', ['status' => 'completed']);
        $responserate = $totalconversations > 0 ? round(($completedconversations / $totalconversations) * 100, 2) : 0;
        
        // Get conversations per day (for chart)
        $conversationschart = self::get_conversations_chart_data();
        
        // Get messages per day (for chart)
        $messageschart = self::get_messages_chart_data();
        
        return [
            'total_conversations' => $totalconversations,
            'total_messages' => $totalmessages,
            'response_rate' => $responserate,
            'conversations_chart' => $conversationschart,
            'messages_chart' => $messageschart
        ];
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function get_stats_returns() {
        return new external_single_structure([
            'total_conversations' => new external_value(PARAM_INT, 'Total number of conversations'),
            'total_messages' => new external_value(PARAM_INT, 'Total number of messages'),
            'response_rate' => new external_value(PARAM_FLOAT, 'Response rate percentage'),
            'conversations_chart' => new external_multiple_structure(
                new external_single_structure([
                    'date' => new external_value(PARAM_TEXT, 'Date'),
                    'count' => new external_value(PARAM_INT, 'Number of conversations')
                ])
            ),
            'messages_chart' => new external_multiple_structure(
                new external_single_structure([
                    'date' => new external_value(PARAM_TEXT, 'Date'),
                    'user' => new external_value(PARAM_INT, 'Number of user messages'),
                    'bot' => new external_value(PARAM_INT, 'Number of bot messages')
                ])
            )
        ]);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function get_conversations_parameters() {
        return new external_function_parameters([
            'date_filter' => new external_value(PARAM_ALPHA, 'Date filter', VALUE_DEFAULT, 'all'),
            'page' => new external_value(PARAM_INT, 'Page number', VALUE_DEFAULT, 1)
        ]);
    }

    /**
     * Get conversations list
     *
     * @param string $datefilter Date filter
     * @param int $page Page number
     * @return array
     */
    public static function get_conversations($datefilter = 'all', $page = 1) {
        global $DB;
        
        $params = self::validate_parameters(self::get_conversations_parameters(), [
            'date_filter' => $datefilter,
            'page' => $page
        ]);
        
        $datefilter = $params['date_filter'];
        $page = $params['page'];
        
        // Check capability
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('local/igisflowise:viewanalytics', $context);
        
        // Build where clause based on date filter
        $where = '';
        $sqlparams = [];
        
        switch ($datefilter) {
            case 'today':
                $where = "WHERE " . $DB->sql_regex_date('FROM_UNIXTIME', 'c.timecreated') . " = " . $DB->sql_regex_date('CURDATE()');
                break;
            case 'yesterday':
                $where = "WHERE " . $DB->sql_regex_date('FROM_UNIXTIME', 'c.timecreated') . " = " . 
                         $DB->sql_regex_date('DATE_SUB(CURDATE(), INTERVAL 1 DAY)');
                break;
            case 'last_week':
                $today = time();
                $lastweek = $today - (7 * 24 * 60 * 60);
                $where = "WHERE c.timecreated >= :lastweek";
                $sqlparams['lastweek'] = $lastweek;
                break;
            case 'last_month':
                $today = time();
                $lastmonth = $today - (30 * 24 * 60 * 60);
                $where = "WHERE c.timecreated >= :lastmonth";
                $sqlparams['lastmonth'] = $lastmonth;
                break;
        }
        
        // Pagination
        $perpage = 20;
        $offset = ($page - 1) * $perpage;
        
        // Get total count
        $countquery = "SELECT COUNT(*) FROM {local_igisflowise_convs} c $where";
        $total = $DB->count_records_sql($countquery, $sqlparams);
        
        // Get conversations
        $query = "
            SELECT c.id, c.userid, c.sessionid, c.status, c.timecreated, c.timemodified,
                   (SELECT COUNT(*) FROM {local_igisflowise_messages} m WHERE m.conversationid = c.id) as message_count
            FROM {local_igisflowise_convs} c
            $where
            ORDER BY c.timecreated DESC
        ";
        
        $records = $DB->get_records_sql($query, $sqlparams, $offset, $perpage);
        
        // Format data
        $conversations = [];
        foreach ($records as $record) {
            // Get username if user exists
            $username = '';
            if (!empty($record->userid)) {
                $user = $DB->get_record('user', ['id' => $record->userid], 'id, username, firstname, lastname');
                if ($user) {
                    $username = fullname($user);
                }
            }
            
            $conversations[] = [
                'id' => $record->id,
                'username' => $username ?: get_string('anonymous', 'local_igisflowise'),
                'status' => $record->status,
                'started_at' => userdate($record->timecreated),
                'ended_at' => $record->timemodified ? userdate($record->timemodified) : null,
                'message_count' => $record->message_count
            ];
        }
        
        return [
            'conversations' => $conversations,
            'total' => $total,
            'pages' => ceil($total / $perpage)
        ];
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function get_conversations_returns() {
        return new external_single_structure([
            'conversations' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT, 'Conversation ID'),
                    'username' => new external_value(PARAM_TEXT, 'Username'),
                    'status' => new external_value(PARAM_TEXT, 'Conversation status'),
                    'started_at' => new external_value(PARAM_TEXT, 'Start time'),
                    'ended_at' => new external_value(PARAM_TEXT, 'End time', VALUE_OPTIONAL),
                    'message_count' => new external_value(PARAM_INT, 'Number of messages')
                ])
            ),
            'total' => new external_value(PARAM_INT, 'Total number of conversations'),
            'pages' => new external_value(PARAM_INT, 'Total number of pages')
        ]);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function get_conversation_details_parameters() {
        return new external_function_parameters([
            'conversation_id' => new external_value(PARAM_INT, 'Conversation ID')
        ]);
    }

    /**
     * Get details of a specific conversation
     *
     * @param int $conversationid Conversation ID
     * @return array
     */
    public static function get_conversation_details($conversationid) {
        global $DB;
        
        $params = self::validate_parameters(self::get_conversation_details_parameters(), [
            'conversation_id' => $conversationid
        ]);
        
        $conversationid = $params['conversation_id'];
        
        // Check capability
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('local/igisflowise:viewanalytics', $context);
        
        // Get conversation details
        $conversation = $DB->get_record('local_igisflowise_convs', ['id' => $conversationid]);
        if (!$conversation) {
            throw new moodle_exception('notfound', 'local_igisflowise', '', null, 'Conversation not found');
        }
        
        // Get user details
        $username = '';
        if (!empty($conversation->userid)) {
            $user = $DB->get_record('user', ['id' => $conversation->userid], 'id, username, firstname, lastname');
            if ($user) {
                $username = fullname($user);
            }
        }
        
        // Get messages
        $messages = $DB->get_records('local_igisflowise_messages', 
                                    ['conversationid' => $conversationid], 
                                    'timecreated ASC');
        
        // Format messages
        $messagedata = [];
        foreach ($messages as $message) {
            $messagedata[] = [
                'id' => $message->id,
                'message' => $message->message,
                'type' => $message->type,
                'timestamp' => userdate($message->timecreated)
            ];
        }
        
        return [
            'conversation' => [
                'id' => $conversation->id,
                'username' => $username ?: get_string('anonymous', 'local_igisflowise'),
                'status' => $conversation->status,
                'started_at' => userdate($conversation->timecreated),
                'ended_at' => $conversation->timemodified ? userdate($conversation->timemodified) : null
            ],
            'messages' => $messagedata
        ];
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function get_conversation_details_returns() {
        return new external_single_structure([
            'conversation' => new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Conversation ID'),
                'username' => new external_value(PARAM_TEXT, 'Username'),
                'status' => new external_value(PARAM_TEXT, 'Conversation status'),
                'started_at' => new external_value(PARAM_TEXT, 'Start time'),
                'ended_at' => new external_value(PARAM_TEXT, 'End time', VALUE_OPTIONAL)
            ]),
            'messages' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT, 'Message ID'),
                    'message' => new external_value(PARAM_RAW, 'Message content'),
                    'type' => new external_value(PARAM_TEXT, 'Message type (user or bot)'),
                    'timestamp' => new external_value(PARAM_TEXT, 'Message timestamp')
                ])
            )
        ]);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function delete_conversation_parameters() {
        return new external_function_parameters([
            'conversation_id' => new external_value(PARAM_INT, 'Conversation ID')
        ]);
    }

    /**
     * Delete a conversation
     *
     * @param int $conversationid Conversation ID
     * @return array
     */
    public static function delete_conversation($conversationid) {
        global $DB;
        
        $params = self::validate_parameters(self::delete_conversation_parameters(), [
            'conversation_id' => $conversationid
        ]);
        
        $conversationid = $params['conversation_id'];
        
        // Check capability
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('local/igisflowise:managebot', $context);
        
        // Check if conversation exists
        if (!$DB->record_exists('local_igisflowise_convs', ['id' => $conversationid])) {
            throw new moodle_exception('notfound', 'local_igisflowise', '', null, 'Conversation not found');
        }
        
        // Delete messages first
        $DB->delete_records('local_igisflowise_messages', ['conversationid' => $conversationid]);
        
        // Delete conversation
        $DB->delete_records('local_igisflowise_convs', ['id' => $conversationid]);
        
        return ['success' => true];
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function delete_conversation_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the deletion was successful')
        ]);
    }

    /**
     * Get data for conversations chart
     *
     * @return array Array of date => count pairs for the last 30 days
     */
    private static function get_conversations_chart_data() {
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
     * Get data for messages chart
     *
     * @return array Array of date => user/bot counts for the last 30 days
     */
    private static function get_messages_chart_data() {
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
}
