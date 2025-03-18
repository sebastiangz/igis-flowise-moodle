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
 * Privacy provider implementation for block_flowise_bot
 *
 * @package    block_flowise_bot
 * @copyright  2025 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_flowise_bot\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\deletion_criteria;
use core_privacy\local\request\helper;
use core_privacy\local\request\writer;

/**
 * Privacy provider implementation for block_flowise_bot
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider {

    /**
     * Returns metadata about the plugin's data storage
     *
     * @param collection $collection The initialised collection to add items to.
     * @return collection The updated collection
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
            'block_flowise_bot_conversations',
            [
                'userid' => 'privacy:metadata:block_flowise_bot:conversations:userid',
                'sessionid' => 'privacy:metadata:block_flowise_bot:conversations:sessionid',
                'courseid' => 'privacy:metadata:block_flowise_bot:conversations:courseid',
                'contextid' => 'privacy:metadata:block_flowise_bot:conversations:contextid',
                'status' => 'privacy:metadata:block_flowise_bot:conversations:status',
                'started' => 'privacy:metadata:block_flowise_bot:conversations:started',
                'ended' => 'privacy:metadata:block_flowise_bot:conversations:ended',
                'metadata' => 'privacy:metadata:block_flowise_bot:conversations:metadata'
            ],
            'privacy:metadata:block_flowise_bot:conversations'
        );

        $collection->add_database_table(
            'block_flowise_bot_messages',
            [
                'conversationid' => 'privacy:metadata:block_flowise_bot:messages:conversationid',
                'message' => 'privacy:metadata:block_flowise_bot:messages:message',
                'sender' => 'privacy:metadata:block_flowise_bot:messages:sender',
                'timecreated' => 'privacy:metadata:block_flowise_bot:messages:timecreated',
                'metadata' => 'privacy:metadata:block_flowise_bot:messages:metadata'
            ],
            'privacy:metadata:block_flowise_bot:messages'
        );

        // External services used
        $collection->add_external_location_link(
            'flowise_api',
            [
                'query' => 'privacy:metadata:flowise_api:query',
                'usage_context' => 'privacy:metadata:flowise_api:usage_context'
            ],
            'privacy:metadata:flowise_api'
        );

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     * @return contextlist The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();

        // Add contexts from block_flowise_bot_conversations
        $sql = "SELECT c.contextid
                  FROM {block_flowise_bot_conversations} c
                 WHERE c.userid = :userid";

        $params = [
            'userid' => $userid
        ];

        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist)) {
            return;
        }

        $user = $contextlist->get_user();
        $userid = $user->id;

        // Export conversations and their messages
        $sql = "SELECT c.id, c.sessionid, c.courseid, c.contextid, c.status, c.started, c.ended, c.metadata
                  FROM {block_flowise_bot_conversations} c
                 WHERE c.userid = :userid";
        
        $params = [
            'userid' => $userid
        ];

        $conversations = $DB->get_records_sql($sql, $params);

        foreach ($conversations as $conversation) {
            $context = \context::instance_by_id($conversation->contextid);
            
            // Export conversation data
            $conversationdata = [
                'sessionid' => $conversation->sessionid,
                'courseid' => $conversation->courseid,
                'contextid' => $conversation->contextid,
                'status' => $conversation->status,
                'started' => transform::datetime($conversation->started),
                'ended' => $conversation->ended ? transform::datetime($conversation->ended) : null,
                'metadata' => $conversation->metadata
            ];
            
            // Add conversation messages
            $messages = $DB->get_records('block_flowise_bot_messages', ['conversationid' => $conversation->id], 'timecreated ASC');
            
            $messagedata = array_map(function($message) {
                return [
                    'message' => $message->message,
                    'sender' => $message->sender,
                    'timecreated' => transform::datetime($message->timecreated),
                    'metadata' => $message->metadata
                ];
            }, $messages);
            
            $conversationdata['messages'] = $messagedata;
            
            // Write the data
            $subcontext = [
                get_string('privacy:conversationspath', 'block_flowise_bot'),
                $conversation->id
            ];
            
            writer::with_context($context)->export_data($subcontext, (object)$conversationdata);
        }
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;
        
        // Find all conversations in this context
        $conversations = $DB->get_records('block_flowise_bot_conversations', ['contextid' => $context->id]);
        
        foreach ($conversations as $conversation) {
            // Delete all messages for this conversation
            $DB->delete_records('block_flowise_bot_messages', ['conversationid' => $conversation->id]);
        }
        
        // Delete all conversations in this context
        $DB->delete_records('block_flowise_bot_conversations', ['contextid' => $context->id]);
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;
        
        if (empty($contextlist->count())) {
            return;
        }
        
        $userid = $contextlist->get_user()->id;
        
        // Find all conversations for this user
        $conversations = $DB->get_records('block_flowise_bot_conversations', ['userid' => $userid]);
        
        foreach ($conversations as $conversation) {
            // Delete all messages for this conversation
            $DB->delete_records('block_flowise_bot_messages', ['conversationid' => $conversation->id]);
        }
        
        // Delete all conversations for this user
        $DB->delete_records('block_flowise_bot_conversations', ['userid' => $userid]);
    }
}