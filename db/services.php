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
 * External functions for block_flowise_bot
 *
 * @package    block_flowise_bot
 * @copyright  2025 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * Block Flowise Bot external functions
 */
class block_flowise_bot_external extends external_api {

    /**
     * Log a conversation with the bot
     *
     * @param string $session_id The session ID
     * @param string $status The status of the conversation (active, completed)
     * @param int $context_id The context ID where the conversation takes place
     * @param int $course_id The course ID where the conversation takes place
     * @return array Result information
     */
    public static function log_conversation($session_id, $status, $context_id, $course_id) {
        global $DB, $USER;

        // Parameter validation
        $params = self::validate_parameters(self::log_conversation_parameters(),
            array(
                'session_id' => $session_id,
                'status' => $status,
                'context_id' => $context_id,
                'course_id' => $course_id
            )
        );

        // Check if saving conversations is enabled
        $config = get_config('block_flowise_bot');
        if (empty($config->save_conversations)) {
            return array(
                'success' => true,
                'message' => 'Conversation logging is disabled',
                'conversation_id' => 0
            );
        }

        // Check if a conversation with this session_id exists
        $conversation = $DB->get_record('block_flowise_bot_conversations', array('sessionid' => $params['session_id']));
        
        if ($conversation) {
            // Update existing conversation
            $conversation->status = $params['status'];
            
            if ($params['status'] === 'completed' && !$conversation->ended) {
                $conversation->ended = time();
            }
            
            $DB->update_record('block_flowise_bot_conversations', $conversation);
            $conversation_id = $conversation->id;
        } else {
            // Create new conversation
            $conversation = new stdClass();
            $conversation->userid = $USER->id;
            $conversation->courseid = $params['course_id'];
            $conversation->contextid = $params['context_id'];
            $conversation->sessionid = $params['session_id'];
            $conversation->status = $params['status'];
            $conversation->started = time();
            
            $conversation_id = $DB->insert_record('block_flowise_bot_conversations', $conversation);
        }
        
        return array(
            'success' => true,
            'message' => 'Conversation ' . ($status === 'active' ? 'started' : 'updated'),
            'conversation_id' => $conversation_id
        );
    }

    /**
     * Parameter definition for log_conversation
     *
     * @return external_function_parameters
     */
    public static function log_conversation_parameters() {
        return new external_function_parameters(
            array(
                'session_id' => new external_value(PARAM_TEXT, 'Session ID'),
                'status' => new external_value(PARAM_TEXT, 'Conversation status'),
                'context_id' => new external_value(PARAM_INT, 'Context ID'),
                'course_id' => new external_value(PARAM_INT, 'Course ID')
            )
        );
    }

    /**
     * Return definition for log_conversation
     *
     * @return external_single_structure
     */
    public static function log_conversation_returns() {
        return new external_single_structure(
            array(
                'success' => new external_value(PARAM_BOOL, 'Whether the operation was successful'),
                'message' => new external_value(PARAM_TEXT, 'Status message'),
                'conversation_id' => new external_value(PARAM_INT, 'Conversation ID')
            )
        );
    }

    /**
     * Log a message in a conversation
     *
     * @param int $conversation_id The conversation ID
     * @param string $message The message content
     * @param string $sender Who sent the message (user or bot)
     * @return array Result information
     */
    public static function log_message($conversation_id, $message, $sender) {
        global $DB;

        // Parameter validation
        $params = self::validate_parameters(self::log_message_parameters(),
            array(
                'conversation_id' => $conversation_id,
                'message' => $message,
                'sender' => $sender
            )
        );

        // Check if saving conversations is enabled
        $config = get_config('block_flowise_bot');
        if (empty($config->save_conversations)) {
            return array(
                'success' => true,
                'message' => 'Message logging is disabled',
                'message_id' => 0
            );
        }

        // Verify conversation exists
        if (!$DB->record_exists('block_flowise_bot_conversations', array('id' => $params['conversation_id']))) {
            return array(
                'success' => false,
                'message' => 'Conversation not found',
                'message_id' => 0
            );
        }

        // Create message record
        $message = new stdClass();
        $message->conversationid = $params['conversation_id'];
        $message->message = $params['message'];
        $message->sender = $params['sender'];
        $message->timecreated = time();

        $message_id = $DB->insert_record('block_flowise_bot_messages', $message);

        return array(
            'success' => true,
            'message' => 'Message logged',
            'message_id' => $message_id
        );
    }

    /**
     * Parameter definition for log_message
     *
     * @return external_function_parameters
     */
    public static function log_message_parameters() {
        return new external_function_parameters(
            array(
                'conversation_id' => new external_value(PARAM_INT, 'Conversation ID'),
                'message' => new external_value(PARAM_RAW, 'Message content'),
                'sender' => new external_value(PARAM_TEXT, 'Message sender (user or bot)')
            )
        );
    }

    /**
     * Return definition for log_message
     *
     * @return external_single_structure
     */
    public static function log_message_returns() {
        return new external_single_structure(
            array(
                'success' => new external_value(PARAM_BOOL, 'Whether the operation was successful'),
                'message' => new external_value(PARAM_TEXT, 'Status message'),
                'message_id' => new external_value(PARAM_INT, 'Message ID')
            )
        );
    }
}
