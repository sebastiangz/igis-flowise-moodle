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
 * Task to clean up old data from block_flowise_bot
 *
 * @package    block_flowise_bot
 * @copyright  2025 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_flowise_bot\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Task to clean up old conversations and messages
 */
class cleanup_old_data extends \core\task\scheduled_task {

    /**
     * Get the name of the task
     *
     * @return string Task name
     */
    public function get_name() {
        return get_string('task_cleanup_name', 'block_flowise_bot');
    }

    /**
     * Execute the task
     */
    public function execute() {
        global $DB;

        $config = get_config('block_flowise_bot');
        $retention_days = isset($config->data_retention_days) ? $config->data_retention_days : 90;

        // Only delete data if retention is set to a valid number of days
        if ($retention_days > 0) {
            $cutoff_time = time() - ($retention_days * 24 * 60 * 60);

            // Log the operation
            mtrace('Cleaning up block_flowise_bot conversations older than ' . userdate($cutoff_time));

            // Get old conversations
            $old_conversations = $DB->get_records_select(
                'block_flowise_bot_conversations',
                'started < :cutoff_time',
                ['cutoff_time' => $cutoff_time]
            );

            $count_conversations = count($old_conversations);
            $count_messages = 0;

            // Delete messages and conversations
            foreach ($old_conversations as $conversation) {
                // Count and delete messages
                $count_messages += $DB->count_records('block_flowise_bot_messages', ['conversationid' => $conversation->id]);
                $DB->delete_records('block_flowise_bot_messages', ['conversationid' => $conversation->id]);
            }

            // Delete the conversations
            $DB->delete_records_select(
                'block_flowise_bot_conversations',
                'started < :cutoff_time',
                ['cutoff_time' => $cutoff_time]
            );

            mtrace("Deleted $count_conversations conversations and $count_messages messages.");
        } else {
            mtrace('Data retention is set to keep all data. No cleanup performed.');
        }
    }
}
