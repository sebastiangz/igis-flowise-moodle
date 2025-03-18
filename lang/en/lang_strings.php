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
 * Language strings for block_flowise_bot
 *
 * @package    block_flowise_bot
 * @copyright  2025 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Flowise Bot';
$string['flowise_bot:addinstance'] = 'Add a new Flowise Bot block';
$string['flowise_bot:myaddinstance'] = 'Add a new Flowise Bot block to Dashboard';
$string['configrequired'] = 'This block needs configuration. Please set up the required fields.';
$string['configurebot'] = 'Configure Bot Settings';
$string['privacy:metadata:block_flowise_bot:conversations'] = 'Information about user conversations with the bot';
$string['privacy:metadata:block_flowise_bot:conversations:userid'] = 'The ID of the user who had the conversation';
$string['privacy:metadata:block_flowise_bot:conversations:sessionid'] = 'A unique identifier for the conversation session';
$string['privacy:metadata:block_flowise_bot:conversations:created'] = 'The time when the conversation was started';
$string['privacy:metadata:block_flowise_bot:conversations:ended'] = 'The time when the conversation ended';
$string['privacy:metadata:block_flowise_bot:messages'] = 'Messages sent in a conversation with the bot';
$string['privacy:metadata:block_flowise_bot:messages:conversationid'] = 'The ID of the conversation this message belongs to';
$string['privacy:metadata:block_flowise_bot:messages:message'] = 'The content of the message';
$string['privacy:metadata:block_flowise_bot:messages:sender'] = 'Who sent the message (user or bot)';
$string['privacy:metadata:block_flowise_bot:messages:timecreated'] = 'The time when the message was sent';

// Settings
$string['settings'] = 'Flowise Bot Settings';
$string['generalconfig'] = 'General Configuration';
$string['appearanceconfig'] = 'Appearance Configuration';
$string['messagesconfig'] = 'Messages Configuration';
$string['displayconfig'] = 'Display Configuration';
$string['advancedconfig'] = 'Advanced Configuration';

// General Configuration
$string['chatflow_id'] = 'Chatflow ID';
$string['chatflow_id_desc'] = 'The ID of the chatflow from your Flowise instance';
$string['api_host'] = 'API Host';
$string['api_host_desc'] = 'The URL of your Flowise API (e.g., https://your-flowise-instance.com)';

// Appearance Configuration
$string['button_color'] = 'Button Color';
$string['button_color_desc'] = 'The background color of the chat button';
$string['button_position_right'] = 'Button Position Right';
$string['button_position_right_desc'] = 'Distance from the right edge of the screen in pixels';
$string['button_position_bottom'] = 'Button Position Bottom';
$string['button_position_bottom_desc'] = 'Distance from the bottom edge of the screen in pixels';
$string['button_size'] = 'Button Size';
$string['button_size_desc'] = 'Size of the chat button in pixels';
$string['enable_drag'] = 'Enable Drag';
$string['enable_drag_desc'] = 'Allow users to drag the chat button to a different position';
$string['icon_color'] = 'Icon Color';
$string['icon_color_desc'] = 'Color of the icon inside the chat button';
$string['custom_icon'] = 'Custom Icon';
$string['custom_icon_desc'] = 'URL to a custom icon for the chat button (SVG recommended)';
$string['window_title'] = 'Window Title';
$string['window_title_desc'] = 'Title displayed at the top of the chat window';
$string['window_height'] = 'Window Height';
$string['window_height_desc'] = 'Height of the chat window in pixels';
$string['window_width'] = 'Window Width';
$string['window_width_desc'] = 'Width of the chat window in pixels';
$string['window_background_color'] = 'Window Background Color';
$string['window_background_color_desc'] = 'Background color of the chat window';
$string['font_size'] = 'Font Size';
$string['font_size_desc'] = 'Font size for the chat text in pixels';

// Messages Configuration
$string['welcome_message'] = 'Welcome Message';
$string['welcome_message_desc'] = 'Message displayed when the chat is first opened';
$string['error_message'] = 'Error Message';
$string['error_message_desc'] = 'Message displayed when an error occurs';
$string['bot_message_bg_color'] = 'Bot Message Background Color';
$string['bot_message_bg_color_desc'] = 'Background color of messages from the bot';
$string['bot_message_text_color'] = 'Bot Message Text Color';
$string['bot_message_text_color_desc'] = 'Text color of messages from the bot';
$string['user_message_bg_color'] = 'User Message Background Color';
$string['user_message_bg_color_desc'] = 'Background color of messages from the user';
$string['user_message_text_color'] = 'User Message Text Color';
$string['user_message_text_color_desc'] = 'Text color of messages from the user';
$string['input_placeholder'] = 'Input Placeholder';
$string['input_placeholder_desc'] = 'Placeholder text for the input field';
$string['input_bg_color'] = 'Input Background Color';
$string['input_bg_color_desc'] = 'Background color of the input field';
$string['input_text_color'] = 'Input Text Color';
$string['input_text_color_desc'] = 'Text color of the input field';
$string['input_send_button_color'] = 'Send Button Color';
$string['input_send_button_color_desc'] = 'Color of the send button';
$string['starter_prompts'] = 'Starter Prompts';
$string['starter_prompts_desc'] = 'Suggested prompts for users to click on (one per line)';

// Display Configuration
$string['display_pages'] = 'Display Pages';
$string['display_pages_desc'] = 'Which pages to display the bot on';
$string['auto_open'] = 'Auto Open';
$string['auto_open_desc'] = 'Automatically open the chat window when the page loads';
$string['auto_open_delay'] = 'Auto Open Delay';
$string['auto_open_delay_desc'] = 'Delay in seconds before automatically opening the chat window';
$string['auto_open_mobile'] = 'Auto Open on Mobile';
$string['auto_open_mobile_desc'] = 'Automatically open the chat on mobile devices';
$string['show_for_logged_in'] = 'Show for Logged-in Users Only';
$string['show_for_logged_in_desc'] = 'Only show the bot for logged-in users';
$string['show_for_roles'] = 'Show for Specific Roles';
$string['show_for_roles_desc'] = 'Only show the bot for users with specific roles';
$string['hide_on_mobile'] = 'Hide on Mobile';
$string['hide_on_mobile_desc'] = 'Hide the bot on mobile devices';
$string['show_tooltip'] = 'Show Tooltip';
$string['show_tooltip_desc'] = 'Show a tooltip next to the chat button';
$string['tooltip_message'] = 'Tooltip Message';
$string['tooltip_message_desc'] = 'Message displayed in the tooltip';
$string['tooltip_bg_color'] = 'Tooltip Background Color';
$string['tooltip_bg_color_desc'] = 'Background color of the tooltip';
$string['tooltip_text_color'] = 'Tooltip Text Color';
$string['tooltip_text_color_desc'] = 'Text color of the tooltip';

// Footer Configuration
$string['footer_text'] = 'Footer Text';
$string['footer_text_desc'] = 'Text displayed in the footer of the chat window';
$string['footer_text_color'] = 'Footer Text Color';
$string['footer_text_color_desc'] = 'Color of the footer text';
$string['footer_company'] = 'Footer Company';
$string['footer_company_desc'] = 'Company name displayed in the footer';
$string['footer_company_link'] = 'Footer Company Link';
$string['footer_company_link_desc'] = 'Link for the company name in the footer';

// Advanced Configuration
$string['custom_css'] = 'Custom CSS';
$string['custom_css_desc'] = 'Custom CSS to style the chat bot';
$string['custom_js'] = 'Custom JavaScript';
$string['custom_js_desc'] = 'Custom JavaScript for the chat bot';
$string['save_conversations'] = 'Save Conversations';
$string['save_conversations_desc'] = 'Save user conversations in the database';
$string['debug_mode'] = 'Debug Mode';
$string['debug_mode_desc'] = 'Enable debug logging for the chat bot';

// Display pages options
$string['page_all'] = 'All pages';
$string['page_site_index'] = 'Site home';
$string['page_course_view'] = 'Course pages';
$string['page_my'] = 'Dashboard';
$string['page_mod'] = 'Activity pages';

// Analytics
$string['analytics'] = 'Analytics';
$string['total_conversations'] = 'Total conversations';
$string['total_messages'] = 'Total messages';
$string['average_conversation_length'] = 'Average conversation length';
$string['most_common_queries'] = 'Most common queries';
$string['no_data_available'] = 'No data available yet';
