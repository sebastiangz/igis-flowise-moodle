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
 * JavaScript for the analytics page
 *
 * @module     local_igisflowise/analytics
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/ajax', 'core/str', 'core/notification', 'core/modal_factory', 'core/modal_events', 'core/templates', 'core/chartjs'], 
function($, Ajax, Str, Notification, ModalFactory, ModalEvents, Templates, Chart) {
    
    /**
     * Module initialization
     */
    function init() {
        // Load charts and data
        loadStats();
        loadConversations();
        
        // Event listeners
        $('#filter-button').on('click', function() {
            loadConversations();
        });
        
        // Delegate for view and delete buttons that will be added dynamically
        $(document).on('click', '.view-conversation', function() {
            const conversationId = $(this).data('id');
            viewConversation(conversationId);
        });
        
        $(document).on('click', '.delete-conversation', function() {
            const conversationId = $(this).data('id');
            deleteConversation(conversationId);
        });
    }
    
    /**
     * Load statistics data
     */
    function loadStats() {
        const request = {
            methodname: 'local_igisflowise_get_stats',
            args: {}
        };
        
        Ajax.call([request])[0].done(function(response) {
            if (response) {
                renderCharts(response);
            } else {
                Notification.exception(new Error(M.util.get_string('error_loading', 'local_igisflowise')));
            }
        }).fail(function(error) {
            Notification.exception(error);
        });
    }
    
    /**
     * Render charts using statistics data
     * 
     * @param {Object} data Statistics data
     */
    function renderCharts(data) {
        // Conversations chart
        if ($('#conversationsChart').length && data.conversations_chart) {
            new Chart($('#conversationsChart'), {
                type: 'line',
                data: {
                    labels: data.conversations_chart.map(item => item.date),
                    datasets: [{
                        label: M.util.get_string('conversations', 'local_igisflowise'),
                        data: data.conversations_chart.map(item => item.count),
                        backgroundColor: 'rgba(59, 129, 246, 0.2)',
                        borderColor: 'rgba(59, 129, 246, 1)',
                        borderWidth: 1,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: M.util.get_string('conversations_per_day', 'local_igisflowise')
                        },
                        legend: {
                            position: 'top',
                        }
                    },
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
        }
        
        // Messages chart
        if ($('#messagesChart').length && data.messages_chart) {
            new Chart($('#messagesChart'), {
                type: 'bar',
                data: {
                    labels: data.messages_chart.map(item => item.date),
                    datasets: [
                        {
                            label: M.util.get_string('user_messages', 'local_igisflowise'),
                            data: data.messages_chart.map(item => item.user),
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: M.util.get_string('bot_messages', 'local_igisflowise'),
                            data: data.messages_chart.map(item => item.bot),
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: M.util.get_string('messages_per_day', 'local_igisflowise')
                        },
                        legend: {
                            position: 'top',
                        }
                    },
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
        }
    }
    
    /**
     * Load conversations list
     * 
     * @param {number} page Page number to load
     */
    function loadConversations(page = 1) {
        const dateFilter = $('#date-filter').val();
        
        // Show loading indicator
        $('#conversations-list').html('<tr><td colspan="6" class="text-center">' + M.util.get_string('loading', 'local_igisflowise') + '</td></tr>');
        
        const request = {
            methodname: 'local_igisflowise_get_conversations',
            args: {
                date_filter: dateFilter,
                page: page
            }
        };
        
        Ajax.call([request])[0].done(function(response) {
            if (response && response.conversations) {
                renderConversations(response);
            } else {
                $('#conversations-list').html('<tr><td colspan="6" class="text-center">' + M.util.get_string('no_conversations', 'local_igisflowise') + '</td></tr>');
            }
        }).fail(function(error) {
            $('#conversations-list').html('<tr><td colspan="6" class="text-center">' + M.util.get_string('error_loading', 'local_igisflowise') + '</td></tr>');
            Notification.exception(error);
        });
    }
    
    /**
     * Render conversations list
     * 
     * @param {Object} data Conversations data
     */
    function renderConversations(data) {
        const tableBody = $('#conversations-list');
        tableBody.empty();
        
        if (data.conversations.length === 0) {
            tableBody.html('<tr><td colspan="6" class="text-center">' + M.util.get_string('no_conversations', 'local_igisflowise') + '</td></tr>');
            return;
        }
        
        $.each(data.conversations, function(i, conversation) {
            const row = $('<tr></tr>');
            
            row.append('<td>' + conversation.id + '</td>');
            row.append('<td>' + conversation.username + '</td>');
            row.append('<td>' + conversation.started_at + '</td>');
            row.append('<td>' + conversation.message_count + '</td>');
            row.append('<td><span class="status-' + conversation.status + '">' + formatStatus(conversation.status) + '</span></td>');
            
            const actions = $('<td></td>');
            const viewBtn = $('<button class="btn btn-sm btn-info view-conversation mr-2">' + M.util.get_string('view', 'local_igisflowise') + '</button>');
            const deleteBtn = $('<button class="btn btn-sm btn-danger delete-conversation">' + M.util.get_string('delete', 'local_igisflowise') + '</button>');
            
            viewBtn.data('id', conversation.id);
            deleteBtn.data('id', conversation.id);
            
            actions.append(viewBtn);
            actions.append(' ');
            actions.append(deleteBtn);
            
            row.append(actions);
            tableBody.append(row);
        });
        
        // Add pagination
        updatePagination(data.total, data.pages, page);
    }
    
    /**
     * Update pagination controls
     * 
     * @param {number} total Total number of items
     * @param {number} pages Total number of pages
     * @param {number} currentPage Current page number
     */
    function updatePagination(total, pages, currentPage) {
        const pagination = $('.igis-pagination-container');
        
        if (pages <= 1) {
            pagination.empty();
            return;
        }
        
        let html = '<ul class="pagination">';
        
        // First and previous buttons
        if (currentPage > 1) {
            html += '<li class="page-item"><a class="page-link pagination-link" data-page="1" href="#">&laquo;</a></li>';
            html += '<li class="page-item"><a class="page-link pagination-link" data-page="' + (currentPage - 1) + '" href="#">&lsaquo;</a></li>';
        } else {
            html += '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
            html += '<li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>';
        }
        
        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(pages, startPage + 4);
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                html += '<li class="page-item active"><span class="page-link">' + i + '</span></li>';
            } else {
                html += '<li class="page-item"><a class="page-link pagination-link" data-page="' + i + '" href="#">' + i + '</a></li>';
            }
        }
        
        // Next and last buttons
        if (currentPage < pages) {
            html += '<li class="page-item"><a class="page-link pagination-link" data-page="' + (currentPage + 1) + '" href="#">&rsaquo;</a></li>';
            html += '<li class="page-item"><a class="page-link pagination-link" data-page="' + pages + '" href="#">&raquo;</a></li>';
        } else {
            html += '<li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>';
            html += '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
        }
        
        html += '</ul>';
        
        pagination.html(html);
        
        // Add event listeners
        $('.pagination-link').on('click', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            loadConversations(page);
        });
    }
    
    /**
     * View details of a conversation
     * 
     * @param {number} conversationId ID of the conversation to view
     */
    function viewConversation(conversationId) {
        const request = {
            methodname: 'local_igisflowise_get_conversation_details',
            args: {
                conversation_id: conversationId
            }
        };
        
        Ajax.call([request])[0].done(function(response) {
            if (response && response.conversation) {
                showConversationModal(response);
            } else {
                Notification.exception(new Error(M.util.get_string('error_loading', 'local_igisflowise')));
            }
        }).fail(function(error) {
            Notification.exception(error);
        });
    }
    
    /**
     * Show modal with conversation details
     * 
     * @param {Object} data Conversation data
     */
    function showConversationModal(data) {
        const conversation = data.conversation;
        const messages = data.messages;
        
        // Show the modal
        $('#conversation-modal').modal('show');
        
        // Populate conversation info
        let infoHtml = '<div class="info-row">' +
                      '<span class="label">' + M.util.get_string('conversation_id', 'local_igisflowise') + ':</span> ' + 
                      '<span>' + conversation.id + '</span>' +
                      '</div>';
        
        infoHtml += '<div class="info-row">' +
                   '<span class="label">' + M.util.get_string('user', 'local_igisflowise') + ':</span> ' + 
                   '<span>' + conversation.username + '</span>' +
                   '</div>';
        
        infoHtml += '<div class="info-row">' +
                   '<span class="label">' + M.util.get_string('date', 'local_igisflowise') + ':</span> ' + 
                   '<span>' + conversation.started_at + '</span>' +
                   '</div>';
        
        infoHtml += '<div class="info-row">' +
                   '<span class="label">' + M.util.get_string('status', 'local_igisflowise') + ':</span> ' + 
                   '<span class="status-' + conversation.status + '">' + formatStatus(conversation.status) + '</span>' +
                   '</div>';
        
        infoHtml += '<div class="info-row">' +
                   '<span class="label">' + M.util.get_string('duration', 'local_igisflowise') + ':</span> ' + 
                   '<span>' + (conversation.ended_at ? formatDuration(conversation.started_at, conversation.ended_at) : M.util.get_string('in_progress', 'local_igisflowise')) + '</span>' +
                   '</div>';
        
        $('.conversation-info').html(infoHtml);
        
        // Populate messages
        let messagesHtml = '';
        
        if (messages.length === 0) {
            messagesHtml = '<div class="alert alert-info">' + M.util.get_string('no_messages', 'local_igisflowise') + '</div>';
        } else {
            $.each(messages, function(i, message) {
                const isBot = message.type === 'bot';
                messagesHtml += '<div class="message ' + message.type + '">' +
                               '<div class="message-header">' +
                               '<span class="message-sender">' + (isBot ? M.util.get_string('bot', 'local_igisflowise') : M.util.get_string('user', 'local_igisflowise')) + '</span>' +
                               '<span class="message-time">' + message.timestamp + '</span>' +
                               '</div>' +
                               '<div class="message-content">' + formatMessage(message.message) + '</div>' +
                               '</div>';
            });
        }
        
        $('.conversation-messages').html(messagesHtml);
    }
    
    /**
     * Delete a conversation
     * 
     * @param {number} conversationId ID of the conversation to delete
     */
    function deleteConversation(conversationId) {
        Str.get_string('confirm_delete', 'local_igisflowise').done(function(confirmMessage) {
            if (confirm(confirmMessage)) {
                const request = {
                    methodname: 'local_igisflowise_delete_conversation',
                    args: {
                        conversation_id: conversationId
                    }
                };
                
                Ajax.call([request])[0].done(function(response) {
                    if (response && response.success) {
                        // Reload the conversations list
                        loadConversations();
                    } else {
                        Notification.exception(new Error(M.util.get_string('error_loading', 'local_igisflowise')));
                    }
                }).fail(function(error) {
                    Notification.exception(error);
                });
            }
        });
    }
    
    /**
     * Format conversation status for display
     * 
     * @param {string} status The status code
     * @return {string} Formatted status text
     */
    function formatStatus(status) {
        switch (status) {
            case 'active':
                return M.util.get_string('active', 'local_igisflowise');
            case 'completed':
                return M.util.get_string('completed', 'local_igisflowise');
            default:
                return status;
        }
    }
    
    /**
     * Format message text for display
     * 
     * @param {string} message Message text
     * @return {string} Formatted message text
     */
    function formatMessage(message) {
        return message.replace(/\n/g, '<br>');
    }
    
    /**
     * Calculate and format duration between two timestamps
     * 
     * @param {string} startTime Start time
     * @param {string} endTime End time
     * @return {string} Formatted duration
     */
    function formatDuration(startTime, endTime) {
        // Implement duration calculation
        return ''; // Placeholder
    }
    
    return {
        init: init
    };
});
