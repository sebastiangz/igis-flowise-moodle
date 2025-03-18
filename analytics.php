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
 * Analytics page for IGIS Flowise Bot
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Check permissions.
admin_externalpage_setup('localigisflowiseanalytics');
require_capability('local/igisflowise:viewanalytics', context_system::instance());

// Get config.
$config = get_config('local_igisflowise');

// Check if analytics is enabled.
if (empty($config->save_conversations) || $config->save_conversations != 1) {
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_igisflowise']),
             get_string('analytics_disabled', 'local_igisflowise'),
             null,
             \core\output\notification::NOTIFY_WARNING);
}

// Setup page.
$title = get_string('analytics_title', 'local_igisflowise');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->css('/local/igisflowise/styles.css');

// Load additional scripts.
$PAGE->requires->js_call_amd('local_igisflowise/analytics', 'init');

// Stats summary.
$totalconversations = $DB->count_records('local_igisflowise_convs');
$totalmessages = $DB->count_records('local_igisflowise_messages');
$completedconversations = $DB->count_records('local_igisflowise_convs', ['status' => 'completed']);
$responserate = $totalconversations > 0 ? round(($completedconversations / $totalconversations) * 100, 2) : 0;

// Start output.
echo $OUTPUT->header();
echo $OUTPUT->heading($title);

// Display stats cards.
?>
<div class="igis-stats-container">
    <div class="igis-stats-card">
        <h3><?php echo get_string('total_conversations', 'local_igisflowise'); ?></h3>
        <div class="igis-stats-value"><?php echo $totalconversations; ?></div>
    </div>
    
    <div class="igis-stats-card">
        <h3><?php echo get_string('total_messages', 'local_igisflowise'); ?></h3>
        <div class="igis-stats-value"><?php echo $totalmessages; ?></div>
    </div>
    
    <div class="igis-stats-card">
        <h3><?php echo get_string('response_rate', 'local_igisflowise'); ?></h3>
        <div class="igis-stats-value"><?php echo $responserate; ?>%</div>
    </div>
</div>

<div class="igis-stats-charts">
    <div class="igis-chart-container">
        <h3><?php echo get_string('conversations_per_day', 'local_igisflowise'); ?></h3>
        <canvas id="conversationsChart"></canvas>
    </div>
    <div class="igis-chart-container">
        <h3><?php echo get_string('messages_per_day', 'local_igisflowise'); ?></h3>
        <canvas id="messagesChart"></canvas>
    </div>
</div>

<h3><?php echo get_string('conversation_details', 'local_igisflowise'); ?></h3>

<div class="igis-filter-container">
    <select name="date_filter" id="date-filter">
        <option value="all"><?php echo get_string('all_time', 'local_igisflowise'); ?></option>
        <option value="today"><?php echo get_string('today', 'local_igisflowise'); ?></option>
        <option value="yesterday"><?php echo get_string('yesterday', 'local_igisflowise'); ?></option>
        <option value="last_week"><?php echo get_string('last_week', 'local_igisflowise'); ?></option>
        <option value="last_month"><?php echo get_string('last_month', 'local_igisflowise'); ?></option>
    </select>
    <button class="btn btn-primary" id="filter-button"><?php echo get_string('filter', 'local_igisflowise'); ?></button>
</div>

<div class="igis-conversations-container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo get_string('conversation_id', 'local_igisflowise'); ?></th>
                <th><?php echo get_string('user', 'local_igisflowise'); ?></th>
                <th><?php echo get_string('date', 'local_igisflowise'); ?></th>
                <th><?php echo get_string('messages', 'local_igisflowise'); ?></th>
                <th><?php echo get_string('status', 'local_igisflowise'); ?></th>
                <th><?php echo get_string('actions', 'local_igisflowise'); ?></th>
            </tr>
        </thead>
        <tbody id="conversations-list">
            <tr>
                <td colspan="6" class="text-center"><?php echo get_string('loading', 'local_igisflowise'); ?></td>
            </tr>
        </tbody>
    </table>
    <div class="igis-pagination-container"></div>
</div>

<!-- Conversation details modal -->
<div class="modal fade" id="conversation-modal" tabindex="-1" role="dialog" aria-labelledby="conversation-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="conversation-modal-title"><?php echo get_string('conversation_details', 'local_igisflowise'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="conversation-info"></div>
                <div class="conversation-messages"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
// End output.
echo $OUTPUT->footer();
