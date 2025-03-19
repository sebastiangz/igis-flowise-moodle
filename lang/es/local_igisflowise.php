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
 * English language strings
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'IGIS Flowise Bot';
$string['privacy:metadata:local_igisflowise'] = 'The IGIS Flowise Bot may store user messages and interactions for analytics purposes.';

// General settings
$string['general_settings'] = 'General Settings';
$string['general_settings_desc'] = 'Configure the basic settings for Flowise Bot.';
$string['enabled'] = 'Enable Bot';
$string['enabled_desc'] = 'Enable or disable the chatbot throughout the site.';
$string['chatflow_id'] = 'Chatflow ID';
$string['chatflow_id_desc'] = 'The Flowise Chatflow ID to connect with.';
$string['api_host'] = 'API Host';
$string['api_host_desc'] = 'The URL of the Flowise API host (e.g., https://your-flowise-server.com).';

// Appearance settings
$string['appearance_settings'] = 'Appearance Settings';
$string['appearance_settings_desc'] = 'Configure how the chatbot appears on the page.';
$string['button_color'] = 'Button Color';
$string['button_color_desc'] = 'The background color of the chatbot button.';
$string['button_position_right'] = 'Button Right Position';
$string['button_position_right_desc'] = 'The distance from the right edge of the viewport in pixels.';
$string['button_position_bottom'] = 'Button Bottom Position';
$string['button_position_bottom_desc'] = 'The distance from the bottom edge of the viewport in pixels.';
$string['button_size'] = 'Button Size';
$string['button_size_desc'] = 'The size of the chatbot button in pixels.';
$string['enable_drag'] = 'Enable Button Dragging';
$string['enable_drag_desc'] = 'Allow users to drag the chatbot button to a different position.';
$string['icon_color'] = 'Icon Color';
$string['icon_color_desc'] = 'The color of the icon within the chatbot button.';
$string['custom_icon'] = 'Custom Icon URL';
$string['custom_icon_desc'] = 'URL to a custom icon to display on the chatbot button (SVG recommended).';

// Window settings
$string['window_title'] = 'Chat Window Title';
$string['window_title_desc'] = 'The title displayed at the top of the chat window.';
$string['welcome_message'] = 'Welcome Message';
$string['welcome_message_desc'] = 'The message displayed when the chat window is first opened.';
$string['default_welcome_message'] = 'Hello! How can I help you today?';
$string['error_message'] = 'Error Message';
$string['error_message_desc'] = 'The message displayed when an error occurs.';
$string['default_error_message'] = 'Sorry, an error occurred. Please try again.';
$string['window_height'] = 'Chat Window Height';
$string['window_height_desc'] = 'The height of the chat window in pixels.';
$string['window_width'] = 'Chat Window Width';
$string['window_width_desc'] = 'The width of the chat window in pixels.';
$string['window_background_color'] = 'Window Background Color';
$string['window_background_color_desc'] = 'The background color of the chat window.';
$string['font_size'] = 'Font Size';
$string['font_size_desc'] = 'The base font size for text in the chat window (in pixels).';

// Messages settings
$string['messages_settings'] = 'Messages Settings';
$string['messages_settings_desc'] = 'Configure the appearance of chat messages.';
$string['bot_message_bg_color'] = 'Bot Message Background';
$string['bot_message_bg_color_desc'] = 'The background color of messages from the chatbot.';
$string['bot_message_text_color'] = 'Bot Message Text Color';
$string['bot_message_text_color_desc'] = 'The text color of messages from the chatbot.';
$string['bot_avatar_enabled'] = 'Show Bot Avatar';
$string['bot_avatar_enabled_desc'] = 'Display an avatar next to the chatbot messages.';
$string['bot_avatar_src'] = 'Bot Avatar URL';
$string['bot_avatar_src_desc'] = 'URL to the image to use as the chatbot avatar.';
$string['user_message_bg_color'] = 'User Message Background';
$string['user_message_bg_color_desc'] = 'The background color of messages from the user.';
$string['user_message_text_color'] = 'User Message Text Color';
$string['user_message_text_color_desc'] = 'The text color of messages from the user.';
$string['user_avatar_enabled'] = 'Show User Avatar';
$string['user_avatar_enabled_desc'] = 'Display an avatar next to the user messages.';
$string['user_avatar_src'] = 'User Avatar URL';
$string['user_avatar_src_desc'] = 'URL to the image to use as the user avatar.';

// Input settings
$string['input_placeholder'] = 'Input Placeholder';
$string['input_placeholder_desc'] = 'The placeholder text displayed in the message input field.';
$string['default_input_placeholder'] = 'Type your question';
$string['input_bg_color'] = 'Input Background Color';
$string['input_bg_color_desc'] = 'The background color of the message input field.';
$string['input_text_color'] = 'Input Text Color';
$string['input_text_color_desc'] = 'The text color of the message input field.';
$string['input_send_button_color'] = 'Send Button Color';
$string['input_send_button_color_desc'] = 'The color of the send button.';
$string['max_chars'] = 'Maximum Characters';
$string['max_chars_desc'] = 'The maximum number of characters allowed in a message.';

// Display settings
$string['display_settings'] = 'Display Settings';
$string['display_settings_desc'] = 'Configure where and when the chatbot should be displayed.';
$string['display_pages'] = 'Display on Pages';
$string['display_pages_desc'] = 'Select which pages the chatbot should be displayed on.';
$string['all_pages'] = 'All pages';
$string['frontpage'] = 'Front page';
$string['dashboard'] = 'Dashboard';
$string['course_pages'] = 'Course pages';
$string['module_pages'] = 'Module pages';
$string['user_profile'] = 'User profile';
$string['login_page'] = 'Login page';
$string['auto_open'] = 'Auto Open Chat';
$string['auto_open_desc'] = 'Automatically open the chat window when the page loads.';
$string['auto_open_delay'] = 'Auto Open Delay';
$string['auto_open_delay_desc'] = 'The delay in seconds before automatically opening the chat window.';
$string['auto_open_mobile'] = 'Auto Open on Mobile';
$string['auto_open_mobile_desc'] = 'Automatically open the chat window on mobile devices.';
$string['show_for_logged_in'] = 'Show for Logged-in Users Only';
$string['show_for_logged_in_desc'] = 'Only display the chatbot for logged-in users.';
$string['show_for_roles'] = 'Show for Specific Roles';
$string['show_for_roles_desc'] = 'Only display the chatbot for users with specific roles.';
$string['hide_on_mobile'] = 'Hide on Mobile';
$string['hide_on_mobile_desc'] = 'Hide the chatbot on mobile devices.';

// Tooltip settings
$string['show_tooltip'] = 'Show Tooltip';
$string['show_tooltip_desc'] = 'Display a tooltip when the chatbot button is visible.';
$string['tooltip_message'] = 'Tooltip Message';
$string['tooltip_message_desc'] = 'The message displayed in the tooltip.';
$string['default_tooltip_message'] = 'Hi there 👋! Need help?';
$string['tooltip_bg_color'] = 'Tooltip Background Color';
$string['tooltip_bg_color_desc'] = 'The background color of the tooltip.';
$string['tooltip_text_color'] = 'Tooltip Text Color';
$string['tooltip_text_color_desc'] = 'The text color of the tooltip.';
$string['tooltip_font_size'] = 'Tooltip Font Size';
$string['tooltip_font_size_desc'] = 'The font size of the tooltip text in pixels.';

// Starter prompts
$string['starter_prompts'] = 'Starter Prompts';
$string['starter_prompts_desc'] = 'Predefined prompts to help users start a conversation (one per line).';

// Sound settings
$string['enable_send_sound'] = 'Enable Send Sound';
$string['enable_send_sound_desc'] = 'Play a sound when sending a message.';
$string['send_sound_url'] = 'Send Sound URL';
$string['send_sound_url_desc'] = 'URL to a custom sound file to play when sending a message.';
$string['enable_receive_sound'] = 'Enable Receive Sound';
$string['enable_receive_sound_desc'] = 'Play a sound when receiving a message.';
$string['receive_sound_url'] = 'Receive Sound URL';
$string['receive_sound_url_desc'] = 'URL to a custom sound file to play when receiving a message.';

// Footer settings
$string['footer_text'] = 'Footer Text';
$string['footer_text_desc'] = 'Text to display in the footer of the chat window.';
$string['footer_text_color'] = 'Footer Text Color';
$string['footer_text_color_desc'] = 'The color of the footer text.';
$string['footer_company'] = 'Company Name';
$string['footer_company_desc'] = 'The name of the company to display in the footer.';
$string['footer_company_link'] = 'Company Link';
$string['footer_company_link_desc'] = 'URL to the company website.';

// Disclaimer settings
$string['show_disclaimer'] = 'Show Disclaimer';
$string['show_disclaimer_desc'] = 'Display a disclaimer message when the chat window is first opened.';
$string['disclaimer_title'] = 'Disclaimer Title';
$string['disclaimer_title_desc'] = 'The title of the disclaimer message.';
$string['disclaimer_message'] = 'Disclaimer Message';
$string['disclaimer_message_desc'] = 'The content of the disclaimer message.';
$string['disclaimer_button_text'] = 'Disclaimer Button Text';
$string['disclaimer_button_text_desc'] = 'The text on the button to dismiss the disclaimer.';
$string['disclaimer_button_color'] = 'Disclaimer Button Color';
$string['disclaimer_button_color_desc'] = 'The color of the disclaimer button.';
$string['disclaimer_text_color'] = 'Disclaimer Text Color';
$string['disclaimer_text_color_desc'] = 'The color of the disclaimer text.';
$string['disclaimer_bg_color'] = 'Disclaimer Background Color';
$string['disclaimer_bg_color_desc'] = 'The background color of the disclaimer.';
$string['disclaimer_overlay_color'] = 'Disclaimer Overlay Color';
$string['disclaimer_overlay_color_desc'] = 'The color of the overlay behind the disclaimer (supports rgba).';

// Advanced settings
$string['advanced_settings'] = 'Advanced Settings';
$string['advanced_settings_desc'] = 'Advanced configuration options for the chatbot.';
$string['custom_css'] = 'Custom CSS';
$string['custom_css_desc'] = 'Add custom CSS styles to modify the appearance of the chatbot.';
$string['custom_js'] = 'Custom JavaScript';
$string['custom_js_desc'] = 'Add custom JavaScript code to extend the functionality of the chatbot.';
$string['custom_headers'] = 'Custom Headers';
$string['custom_headers_desc'] = 'Add custom headers to be sent with API requests (in JSON format).';
$string['rate_limiting'] = 'Rate Limiting';
$string['rate_limiting_desc'] = 'Maximum number of messages a user can send per minute.';
$string['session_timeout'] = 'Session Timeout';
$string['session_timeout_desc'] = 'Number of minutes after which an inactive chat session will timeout.';
$string['debug_mode'] = 'Debug Mode';
$string['debug_mode_desc'] = 'Enable debug mode to log extra information to the browser console.';
$string['save_conversations'] = 'Save Conversations';
$string['save_conversations_desc'] = 'Store chat conversations in the database for analytics.';

// Admin view strings
$string['analytics_title'] = 'Chat Analytics';
$string['total_conversations'] = 'Total Conversations';
$string['total_messages'] = 'Total Messages';
$string['response_rate'] = 'Response Rate';
$string['conversations_per_day'] = 'Conversations per Day';
$string['messages_per_day'] = 'Messages per Day';
$string['date_filter'] = 'Date Filter';
$string['today'] = 'Today';
$string['yesterday'] = 'Yesterday';
$string['last_week'] = 'Last Week';
$string['last_month'] = 'Last Month';
$string['all_time'] = 'All Time';
$string['filter'] = 'Filter';
$string['conversation_details'] = 'Conversation Details';
$string['no_conversations'] = 'No conversations found.';
$string['loading'] = 'Loading...';
$string['error_loading'] = 'Error loading data. Please try again.';
$string['conversation_id'] = 'Conversation ID';
$string['user'] = 'User';
$string['date'] = 'Date';
$string['messages'] = 'Messages';
$string['status'] = 'Status';
$string['actions'] = 'Actions';
$string['view'] = 'View';
$string['delete'] = 'Delete';
$string['anonymous'] = 'Anonymous';
$string['active'] = 'Active';
$string['completed'] = 'Completed';
$string['duration'] = 'Duration';
$string['in_progress'] = 'In progress';
$string['bot'] = 'Bot';
$string['confirm_delete'] = 'Are you sure you want to delete this conversation?';

// Installation
$string['install_success'] = 'IGIS Flowise Bot installed successfully.';
$string['tables_created'] = 'Database tables created successfully.';

// Database strings
$string['conversations_table_created'] = 'Conversations table created successfully.';
$string['messages_table_created'] = 'Messages table created successfully.';
$string['analytics_table_created'] = 'Analytics table created successfully.';