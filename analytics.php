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
 * Analytics page for block_flowise_bot
 *
 * @package    block_flowise_bot
 * @copyright  2025 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Check permissions
require_login();
require_capability('block/flowise_bot:viewanalytics', context_system::instance());

// Setup page
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/blocks/flowise_bot/analytics.php'));
$PAGE->set_title(get_string('analytics', 'block_flowise_bot'));
$PAGE->set_heading(get_string('analytics', 'block_flowise_bot'));
$PAGE->set_pagelayout('admin');

// Add JavaScript for charts
$PAGE->requires->js_call_amd('block_flowise_bot/analytics', 'init');

// Get data for analytics
$startdate = optional_param('startdate', strtotime('-30 days'), PARAM_INT);
$enddate = optional_param('enddate', time(), PARAM_INT);

// Get statistics
$stats = get_bot_statistics($startdate, $enddate);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('analytics', 'block_flowise_bot'));

// Date filter form
$dateform = new single_button(
    new moodle_url('/blocks/flowise_bot/analytics.php'),
    get_string('apply', 'core'),
    'post'
);
$dateform->add_action(new popup_action('click', new moodle_url('/calendar/index.php', 
    array('course' => SITEID)), 'calendar', array('width' => 600, 'height' => 600)));

echo html_writer::start_div('date-selector');
echo html_writer::start_tag('form', array('method' => 'get', 'action' => $PAGE->url));
echo html_writer::start_div('form-inline');
echo html_writer::label(get_string('from'), 'startdate', false, array('class' => 'mr-1'));
echo html_writer::empty_tag('input', array(
    'type' => 'date',
    'name' => 'startdate',
    'value' => date('Y-m-d', $startdate),
    'class' => 'form-control mr-2'
));
echo html_writer::label(get_string('to'), 'enddate', false, array('class' => 'mr-1'));
echo html_writer::empty_tag('input', array(
    'type' => 'date',
    'name' => 'enddate',
    'value' => date('Y-m-d', $enddate),
    'class' => 'form-control mr-2'
));
echo html_writer::empty_tag('input', array(
    'type' => 'submit',
    'value' => get_string('apply', 'core'),
    'class' => 'btn btn-primary'
));
echo html_writer::end_div();
echo html_writer::end_tag('form');
echo html_writer::end_div();

// Display statistics
echo html_writer::start_div('container-fluid mt-4');

// Top stats
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-4');
echo html_writer::div($stats->total_conversations, 'stat-value');
echo html_writer::div(get_string('total_conversations', 'block_flowise_bot'), 'stat-label');
echo html_writer::end_div();

echo html_writer::start_div('col-md-4');
echo html_writer::div($stats->total_messages, 'stat-value');
echo html_writer::div(get_string('total_messages', 'block_flowise_bot'), 'stat-label');
echo html_writer::end_div();

echo html_writer::start_div('col-md-4');
echo html_writer::div($stats->avg_conversation_length, 'stat-value');
echo html_writer::div(get_string('average_conversation_length', 'block_flowise_bot'), 'stat-label');
echo html_writer::end_div();
echo html_writer::end_div();

// Charts
echo html_writer::start_div('row mt-4');
echo html_writer::start_div('col-md-6');
echo html_writer::tag('h4', get_string('conversations_over_time', 'block_flowise_bot'));
echo html_writer::div('', 'chart-container', array('id' => 'conversation-chart'));
echo html_writer::end_div();

echo html_writer::start_div('col-md-6');
echo html_writer::tag('h4', get_string('messages_per_day', 'block_flowise_bot'));
echo html_writer::div('', 'chart-container', array('id' => 'message-chart'));
echo html_writer::end_div();
echo html_writer::end_div();

// Common queries
echo html_writer::start_div('row mt-4');
echo html_writer::start_div('col-md-12');
echo html_writer::tag('h4', get_string('most_common_queries', 'block_flowise_bot'));

if (!empty($stats->common_queries)) {
    echo html_writer::start_tag('table', array('class' => 'table table-striped'));
    echo html_writer::start_tag('thead');
    echo html_writer::start_tag('tr');
    echo html_writer::tag('th', get_string('query', 'block_flowise_bot'));
    echo html_writer::tag('th', get_string('count', 'block_flowise_bot'));
    echo html_writer::end_tag('tr');
    echo html_writer::end_tag('thead');
    
    echo html_writer::start_tag('tbody');
    foreach ($stats->common_queries as $query) {
        echo html_writer::start_tag('tr');
        echo html_writer::tag('td', $query->message);
        echo html_writer::tag('td', $query->count);
        echo html_writer::end_tag('tr');
    }
    echo html_writer::end_tag('tbody');
    echo html_writer::end_tag('table');
} else {
    echo html_writer::div(get_string('no_data_available', 'block_flowise_bot'), 'alert alert-info');
}

echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div(); // End container

echo $OUTPUT->footer();

/**
 * Get bot statistics for the given date range
 *
 * @param int $startdate Start timestamp
 * @param int $enddate End timestamp
 * @return stdClass Statistics data
 */
function get_bot_statistics($startdate, $enddate) {
    global $DB;
    
    $stats = new stdClass();
    
    // Total conversations
    $stats->total_conversations = $DB->count_records_select(
        'block_flowise_bot_conversations',
        "started BETWEEN :startdate AND :enddate",
        array('startdate' => $startdate, 'enddate' => $enddate)
    );
    
    // Total messages
    $stats->total_messages = $DB->count_records_sql(
        "SELECT COUNT(m.id) 
         FROM {block_flowise_bot_messages} m
         JOIN {block_flowise_bot_conversations} c ON m.conversationid = c.id
         WHERE c.started BETWEEN :startdate AND :enddate",
        array('startdate' => $startdate, 'enddate' => $enddate)
    );
    
    // Average conversation length (messages per conversation)
    if ($stats->total_conversations > 0) {
        $stats->avg_conversation_length = round($stats->total_messages / $stats->total_conversations, 1);
    } else {
        $stats->avg_conversation_length = 0;
    }
    
    // Get daily conversation count for chart
    $stats->daily_conversations = $DB->get_records_sql(
        "SELECT FROM_UNIXTIME(started, '%Y-%m-%d') as date, COUNT(*) as count
         FROM {block_flowise_bot_conversations}
         WHERE started BETWEEN :startdate AND :enddate
         GROUP BY FROM_UNIXTIME(started, '%Y-%m-%d')
         ORDER BY date",
        array('startdate' => $startdate, 'enddate' => $enddate)
    );
    
    // Get daily message count for chart
    $stats->daily_messages = $DB->get_records_sql(
        "SELECT FROM_UNIXTIME(m.timecreated, '%Y-%m-%d') as date, 
                COUNT(*) as count,
                SUM(CASE WHEN m.sender = 'user' THEN 1 ELSE 0 END) as user_count,
                SUM(CASE WHEN m.sender = 'bot' THEN 1 ELSE 0 END) as bot_count
         FROM {block_flowise_bot_messages} m
         JOIN {block_flowise_bot_conversations} c ON m.conversationid = c.id
         WHERE c.started BETWEEN :startdate AND :enddate
         GROUP BY FROM_UNIXTIME(m.timecreated, '%Y-%m-%d')
         ORDER BY date",
        array('startdate' => $startdate, 'enddate' => $enddate)
    );
    
    // Most common user queries
    $stats->common_queries = $DB->get_records_sql(
        "SELECT m.message, COUNT(*) as count
         FROM {block_flowise_bot_messages} m
         JOIN {block_flowise_bot_conversations} c ON m.conversationid = c.id
         WHERE m.sender = 'user' AND c.started BETWEEN :startdate AND :enddate
         GROUP BY m.message
         ORDER BY count DESC
         LIMIT 10",
        array('startdate' => $startdate, 'enddate' => $enddate)
    );
    
    return $stats;
}
