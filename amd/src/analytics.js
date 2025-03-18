/**
 * JavaScript module for analytics in block_flowise_bot
 *
 * @module     block_flowise_bot/analytics
 * @copyright  2025 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/chartjs'], function($, Chart) {
    
    /**
     * Initialize the analytics charts
     */
    var init = function() {
        fetchAnalyticsData().then(function(data) {
            renderConversationChart(data.daily_conversations);
            renderMessageChart(data.daily_messages);
        }).catch(function(error) {
            console.error('Error loading analytics data:', error);
        });
    };
    
    /**
     * Fetch analytics data from the page
     * 
     * @return {Promise} A promise that resolves with the data
     */
    var fetchAnalyticsData = function() {
        return new Promise(function(resolve) {
            // In a real implementation, this would be an AJAX call to fetch data
            // For now, we'll extract data from the page
            
            // This assumes the data is added to the page via a script tag with the ID 'analytics-data'
            var dataElement = document.getElementById('analytics-data');
            var data = dataElement ? JSON.parse(dataElement.textContent) : {
                daily_conversations: [],
                daily_messages: []
            };
            
            resolve(data);
        });
    };
    
    /**
     * Render the conversations over time chart
     * 
     * @param {Array} data Conversation data
     */
    var renderConversationChart = function(data) {
        var container = document.getElementById('conversation-chart');
        if (!container) {
            return;
        }
        
        var canvas = document.createElement('canvas');
        container.appendChild(canvas);
        
        var dates = [];
        var counts = [];
        
        // Parse data for the chart
        if (data && data.length) {
            data.forEach(function(item) {
                dates.push(item.date);
                counts.push(parseInt(item.count));
            });
        } else {
            // Sample data if no data is available
            var today = new Date();
            for (var i = 6; i >= 0; i--) {
                var date = new Date(today);
                date.setDate(date.getDate() - i);
                dates.push(date.toISOString().slice(0, 10));
                counts.push(0);
            }
        }
        
        // Create the chart
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: M.util.get_string('total_conversations', 'block_flowise_bot'),
                    data: counts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    };
    
    /**
     * Render the messages per day chart
     * 
     * @param {Array} data Message data
     */
    var renderMessageChart = function(data) {
        var container = document.getElementById('message-chart');
        if (!container) {
            return;
        }
        
        var canvas = document.createElement('canvas');
        container.appendChild(canvas);
        
        var dates = [];
        var userCounts = [];
        var botCounts = [];
        
        // Parse data for the chart
        if (data && data.length) {
            data.forEach(function(item) {
                dates.push(item.date);
                userCounts.push(parseInt(item.user_count || 0));
                botCounts.push(parseInt(item.bot_count || 0));
            });
        } else {
            // Sample data if no data is available
            var today = new Date();
            for (var i = 6; i >= 0; i--) {
                var date = new Date(today);
                date.setDate(date.getDate() - i);
                dates.push(date.toISOString().slice(0, 10));
                userCounts.push(0);
                botCounts.push(0);
            }
        }
        
        // Create the chart
        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: M.util.get_string('user_messages', 'block_flowise_bot'),
                        data: userCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: M.util.get_string('bot_messages', 'block_flowise_bot'),
                        data: botCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    };
    
    return {
        init: init
    };
});
