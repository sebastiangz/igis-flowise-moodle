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
 * Privacy Subsystem implementation for local_igisflowise.
 *
 * @package   local_igisflowise
 * @copyright 2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_igisflowise\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\deletion_criteria;
use core_privacy\local\request\helper;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy Subsystem for local_igisflowise implementing provider.
 *
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider,
    \core_privacy\local\request\core_userlist_provider {

    /**
     * Returns meta data about this system.
     *
     * @param collection $collection The initialised collection to add items to.
     * @return collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
            'local_igisflowise_convs',
            [
                'userid' => 'privacy:metadata:local_igisflowise_convs:userid',
                'sessionid' => 'privacy:metadata:local_igisflowise_convs:sessionid',
                'status' => 'privacy:metadata:local_igisflowise_convs:status',
                'timecreated' => 'privacy:metadata:local_igisflowise_convs:timecreated',
                'timemodified' => 'privacy:metadata:local_igisflowise_convs:timemodified',
                'metadata' => 'privacy:metadata:local_igisflowise_convs:metadata'
            ],
            'privacy:metadata:local_igisflowise_convs'
        );

        $collection->add_database_table(
            'local_igisflowise_messages',
            [
                'conversationid' => 'privacy:metadata:local_igisflowise_messages:conversationid',
                'message' => 'privacy:metadata:local_igisflowise_messages:message',
                'type' => 'privacy:metadata:local_igisflowise_messages:type',
                'timecreated' => 'privacy:metadata:local_igisflowise_messages:timecreated',
                'metadata' => 'privacy:metadata:local_igisflowise_messages:metadata'
            ],
            'privacy:metadata:local_igisflowise_messages'
        );

        $collection->add_database_table(
            'local_igisflowise_analytics',
            [
                'eventtype' => 'privacy:metadata:local_igisflowise_analytics:eventtype',
                'eventdata' => 'privacy:metadata:local_igisflowise_analytics:eventdata',
                'timecreated' => 'privacy:metadata:local_igisflowise_analytics:timecreated'
            ],
            'privacy:metadata:local_igisflowise_analytics'
        );

        $collection->add_external_location_link(
            'flowise_api',
            [
                'message' => 'privacy:metadata:flowise_api:message',
                'sessionid' => 'privacy:metadata:flowise_api:sessionid'
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
        
        $sql = "SELECT c.id
                  FROM {context} c
                 WHERE c.instanceid = :siteid
                   AND c.contextlevel = :contextlevel
                   AND EXISTS (
                       SELECT 1 
                         FROM {local_igisflowise_convs} fc
                        WHERE fc.userid = :userid
                   )";
                   
        $params = [
            'siteid' => SITEID,
            'contextlevel' => CONTEXT_SYSTEM,
            'userid' => $userid
        ];
        
        $contextlist->add_from_sql($sql, $params);
        
        return $contextlist;
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        if (!$context instanceof \context_system) {
            return;
        }

        $sql = "SELECT fc.userid as userid
                  FROM {local_igisflowise_convs} fc
                 WHERE fc.userid IS NOT NULL";
        $userlist->add_from_sql('userid', $sql, []);
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $user = $contextlist->get_user();
        $context = \context_system::instance();

        // Export conversations.
        $sql = "SELECT fc.*, fc.id as conversationid
                  FROM {local_igisflowise_convs} fc
                 WHERE fc.userid = :userid";
        $params = ['userid' => $user->id];
        $conversations = $DB->get_records_sql($sql, $params);

        foreach ($conversations as $conversation) {
            $conversationid = $conversation->conversationid;
            
            // Add conversation data.
            $conversationdata = [
                'sessionid' => $conversation->sessionid,
                'status' => $conversation->status,
                'timecreated' => transform::datetime($conversation->timecreated),
                'timemodified' => transform::datetime($conversation->timemodified),
                'metadata' => $conversation->metadata
            ];
            
            // Get messages for this conversation.
            $sql = "SELECT fm.*
                      FROM {local_igisflowise_messages} fm
                     WHERE fm.conversationid = :conversationid
                  ORDER BY fm.timecreated ASC";
            $params = ['conversationid' => $conversationid];
            $messages = $DB->get_records_sql($sql, $params);
            
            $messagedata = [];
            foreach ($messages as $message) {
                $messagedata[] = [
                    'message' => $message->message,
                    'type' => $message->type,
                    'timecreated' => transform::datetime($message->timecreated),
                    'metadata' => $message->metadata
                ];
            }
            
            $conversationdata['messages'] = $messagedata;
            
            // Export the data.
            $subcontext = [
                get_string('pluginname', 'local_igisflowise'),
                get_string('conversations', 'local_igisflowise'),
                $conversation->id
            ];
            
            writer::with_context($context)->export_data($subcontext, $conversationdata);
        }
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;
        
        if (!$context instanceof \context_system) {
            return;
        }
        
        // Delete all conversation records.
        $DB->delete_records('local_igisflowise_messages');
        $DB->delete_records('local_igisflowise_convs');
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
        
        // Get all conversation IDs for this user.
        $conversationids = $DB->get_fieldset_select('local_igisflowise_convs', 'id', 'userid = :userid', ['userid' => $userid]);
        
        if (!empty($conversationids)) {
            list($insql, $inparams) = $DB->get_in_or_equal($conversationids, SQL_PARAMS_NAMED);
            
            // Delete all messages for these conversations.
            $DB->delete_records_select('local_igisflowise_messages', "conversationid $insql", $inparams);
            
            // Delete all conversations.
            $DB->delete_records_select('local_igisflowise_convs', "id $insql", $inparams);
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;
        
        $context = $userlist->get_context();
        
        if (!$context instanceof \context_system) {
            return;
        }
        
        $userids = $userlist->get_userids();
        
        if (empty($userids)) {
            return;
        }
        
        // Get all conversation IDs for these users.
        list($insql, $inparams) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
        $conversationids = $DB->get_fieldset_select('local_igisflowise_convs', 'id', "userid $insql", $inparams);
        
        if (!empty($conversationids)) {
            list($convinsql, $convinparams) = $DB->get_in_or_equal($conversationids, SQL_PARAMS_NAMED);
            
            // Delete all messages for these conversations.
            $DB->delete_records_select('local_igisflowise_messages', "conversationid $convinsql", $convinparams);
            
            // Delete all conversations.
            $DB->delete_records_select('local_igisflowise_convs', "id $convinsql", $convinparams);
        }
    }
}
