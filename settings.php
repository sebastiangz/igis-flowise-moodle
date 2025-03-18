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
 * Settings for block_flowise_bot
 *
 * @package    block_flowise_bot
 * @copyright  2025 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    // General settings heading
    $settings->add(new admin_setting_heading(
        'block_flowise_bot/generalconfig',
        get_string('generalconfig', 'block_flowise_bot'),
        ''
    ));

    // Chatflow ID
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/chatflow_id',
        get_string('chatflow_id', 'block_flowise_bot'),
        get_string('chatflow_id_desc', 'block_flowise_bot'),
        '',
        PARAM_TEXT
    ));

    // API Host
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/api_host',
        get_string('api_host', 'block_flowise_bot'),
        get_string('api_host_desc', 'block_flowise_bot'),
        '',
        PARAM_URL
    ));

    // Appearance settings heading
    $settings->add(new admin_setting_heading(
        'block_flowise_bot/appearanceconfig',
        get_string('appearanceconfig', 'block_flowise_bot'),
        ''
    ));

    // Button Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/button_color',
        get_string('button_color', 'block_flowise_bot'),
        get_string('button_color_desc', 'block_flowise_bot'),
        '#3B81F6'
    ));

    // Button Position Right
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/button_position_right',
        get_string('button_position_right', 'block_flowise_bot'),
        get_string('button_position_right_desc', 'block_flowise_bot'),
        '20',
        PARAM_INT
    ));

    // Button Position Bottom
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/button_position_bottom',
        get_string('button_position_bottom', 'block_flowise_bot'),
        get_string('button_position_bottom_desc', 'block_flowise_bot'),
        '20',
        PARAM_INT
    ));

    // Button Size
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/button_size',
        get_string('button_size', 'block_flowise_bot'),
        get_string('button_size_desc', 'block_flowise_bot'),
        '48',
        PARAM_INT
    ));

    // Enable Drag
    $settings->add(new admin_setting_configcheckbox(
        'block_flowise_bot/enable_drag',
        get_string('enable_drag', 'block_flowise_bot'),
        get_string('enable_drag_desc', 'block_flowise_bot'),
        1
    ));

    // Icon Color
    $settings->add(new admin_setting_configselect(
        'block_flowise_bot/icon_color',
        get_string('icon_color', 'block_flowise_bot'),
        get_string('icon_color_desc', 'block_flowise_bot'),
        'white',
        array(
            'white' => 'White',
            'black' => 'Black'
        )
    ));

    // Custom Icon
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/custom_icon',
        get_string('custom_icon', 'block_flowise_bot'),
        get_string('custom_icon_desc', 'block_flowise_bot'),
        'https://raw.githubusercontent.com/walkxcode/dashboard-icons/main/svg/google-messages.svg',
        PARAM_URL
    ));

    // Window Title
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/window_title',
        get_string('window_title', 'block_flowise_bot'),
        get_string('window_title_desc', 'block_flowise_bot'),
        'Moodle Assistant',
        PARAM_TEXT
    ));

    // Window Height
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/window_height',
        get_string('window_height', 'block_flowise_bot'),
        get_string('window_height_desc', 'block_flowise_bot'),
        '700',
        PARAM_INT
    ));

    // Window Width
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/window_width',
        get_string('window_width', 'block_flowise_bot'),
        get_string('window_width_desc', 'block_flowise_bot'),
        '400',
        PARAM_INT
    ));

    // Window Background Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/window_background_color',
        get_string('window_background_color', 'block_flowise_bot'),
        get_string('window_background_color_desc', 'block_flowise_bot'),
        '#ffffff'
    ));

    // Font Size
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/font_size',
        get_string('font_size', 'block_flowise_bot'),
        get_string('font_size_desc', 'block_flowise_bot'),
        '16',
        PARAM_INT
    ));

    // Messages settings heading
    $settings->add(new admin_setting_heading(
        'block_flowise_bot/messagesconfig',
        get_string('messagesconfig', 'block_flowise_bot'),
        ''
    ));

    // Welcome Message
    $settings->add(new admin_setting_configtextarea(
        'block_flowise_bot/welcome_message',
        get_string('welcome_message', 'block_flowise_bot'),
        get_string('welcome_message_desc', 'block_flowise_bot'),
        'Hello! I\'m your Moodle assistant. How can I help you find the right course or information today?',
        PARAM_TEXT
    ));

    // Error Message
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/error_message',
        get_string('error_message', 'block_flowise_bot'),
        get_string('error_message_desc', 'block_flowise_bot'),
        'Sorry, an error occurred. Please try again.',
        PARAM_TEXT
    ));

    // Bot Message Background Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/bot_message_bg_color',
        get_string('bot_message_bg_color', 'block_flowise_bot'),
        get_string('bot_message_bg_color_desc', 'block_flowise_bot'),
        '#f7f8ff'
    ));

    // Bot Message Text Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/bot_message_text_color',
        get_string('bot_message_text_color', 'block_flowise_bot'),
        get_string('bot_message_text_color_desc', 'block_flowise_bot'),
        '#303235'
    ));

    // User Message Background Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/user_message_bg_color',
        get_string('user_message_bg_color', 'block_flowise_bot'),
        get_string('user_message_bg_color_desc', 'block_flowise_bot'),
        '#3B81F6'
    ));

    // User Message Text Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/user_message_text_color',
        get_string('user_message_text_color', 'block_flowise_bot'),
        get_string('user_message_text_color_desc', 'block_flowise_bot'),
        '#ffffff'
    ));

    // Input Placeholder
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/input_placeholder',
        get_string('input_placeholder', 'block_flowise_bot'),
        get_string('input_placeholder_desc', 'block_flowise_bot'),
        'Type your question',
        PARAM_TEXT
    ));

    // Input Background Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/input_bg_color',
        get_string('input_bg_color', 'block_flowise_bot'),
        get_string('input_bg_color_desc', 'block_flowise_bot'),
        '#ffffff'
    ));

    // Input Text Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/input_text_color',
        get_string('input_text_color', 'block_flowise_bot'),
        get_string('input_text_color_desc', 'block_flowise_bot'),
        '#303235'
    ));

    // Input Send Button Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/input_send_button_color',
        get_string('input_send_button_color', 'block_flowise_bot'),
        get_string('input_send_button_color_desc', 'block_flowise_bot'),
        '#3B81F6'
    ));

    // Starter Prompts
    $settings->add(new admin_setting_configtextarea(
        'block_flowise_bot/starter_prompts',
        get_string('starter_prompts', 'block_flowise_bot'),
        get_string('starter_prompts_desc', 'block_flowise_bot'),
        "What courses are available?\nHow do I enroll in a course?\nWhere can I find course materials?",
        PARAM_TEXT
    ));

    // Display settings heading
    $settings->add(new admin_setting_heading(
        'block_flowise_bot/displayconfig',
        get_string('displayconfig', 'block_flowise_bot'),
        ''
    ));

    // Display Pages
    $pageoptions = [
        'all' => get_string('page_all', 'block_flowise_bot'),
        'site-index' => get_string('page_site_index', 'block_flowise_bot'),
        'course-view' => get_string('page_course_view', 'block_flowise_bot'),
        'my' => get_string('page_my', 'block_flowise_bot'),
        'mod' => get_string('page_mod', 'block_flowise_bot')
    ];
    
    $settings->add(new admin_setting_configmultiselect(
        'block_flowise_bot/display_pages',
        get_string('display_pages', 'block_flowise_bot'),
        get_string('display_pages_desc', 'block_flowise_bot'),
        ['all'],
        $pageoptions
    ));

    // Auto Open
    $settings->add(new admin_setting_configcheckbox(
        'block_flowise_bot/auto_open',
        get_string('auto_open', 'block_flowise_bot'),
        get_string('auto_open_desc', 'block_flowise_bot'),
        0
    ));

    // Auto Open Delay
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/auto_open_delay',
        get_string('auto_open_delay', 'block_flowise_bot'),
        get_string('auto_open_delay_desc', 'block_flowise_bot'),
        '2',
        PARAM_INT
    ));

    // Auto Open on Mobile
    $settings->add(new admin_setting_configcheckbox(
        'block_flowise_bot/auto_open_mobile',
        get_string('auto_open_mobile', 'block_flowise_bot'),
        get_string('auto_open_mobile_desc', 'block_flowise_bot'),
        0
    ));

    // Show for Logged-in Users Only
    $settings->add(new admin_setting_configcheckbox(
        'block_flowise_bot/show_for_logged_in',
        get_string('show_for_logged_in', 'block_flowise_bot'),
        get_string('show_for_logged_in_desc', 'block_flowise_bot'),
        0
    ));

    // Show for Specific Roles
    $roles = get_all_roles();
    $roleoptions = array();
    foreach ($roles as $role) {
        $roleoptions[$role->id] = $role->localname;
    }
    
    $settings->add(new admin_setting_configmultiselect(
        'block_flowise_bot/show_for_roles',
        get_string('show_for_roles', 'block_flowise_bot'),
        get_string('show_for_roles_desc', 'block_flowise_bot'),
        [],
        $roleoptions
    ));

    // Hide on Mobile
    $settings->add(new admin_setting_configcheckbox(
        'block_flowise_bot/hide_on_mobile',
        get_string('hide_on_mobile', 'block_flowise_bot'),
        get_string('hide_on_mobile_desc', 'block_flowise_bot'),
        0
    ));

    // Show Tooltip
    $settings->add(new admin_setting_configcheckbox(
        'block_flowise_bot/show_tooltip',
        get_string('show_tooltip', 'block_flowise_bot'),
        get_string('show_tooltip_desc', 'block_flowise_bot'),
        1
    ));

    // Tooltip Message
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/tooltip_message',
        get_string('tooltip_message', 'block_flowise_bot'),
        get_string('tooltip_message_desc', 'block_flowise_bot'),
        'Hi There 👋! Need help with courses?',
        PARAM_TEXT
    ));

    // Tooltip Background Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/tooltip_bg_color',
        get_string('tooltip_bg_color', 'block_flowise_bot'),
        get_string('tooltip_bg_color_desc', 'block_flowise_bot'),
        '#000000'
    ));

    // Tooltip Text Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/tooltip_text_color',
        get_string('tooltip_text_color', 'block_flowise_bot'),
        get_string('tooltip_text_color_desc', 'block_flowise_bot'),
        '#ffffff'
    ));

    // Footer Text
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/footer_text',
        get_string('footer_text', 'block_flowise_bot'),
        get_string('footer_text_desc', 'block_flowise_bot'),
        'Powered by Moodle AI Assistant',
        PARAM_TEXT
    ));

    // Footer Text Color
    $settings->add(new admin_setting_configcolourpicker(
        'block_flowise_bot/footer_text_color',
        get_string('footer_text_color', 'block_flowise_bot'),
        get_string('footer_text_color_desc', 'block_flowise_bot'),
        '#303235'
    ));

    // Footer Company
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/footer_company',
        get_string('footer_company', 'block_flowise_bot'),
        get_string('footer_company_desc', 'block_flowise_bot'),
        '',
        PARAM_TEXT
    ));

    // Footer Company Link
    $settings->add(new admin_setting_configtext(
        'block_flowise_bot/footer_company_link',
        get_string('footer_company_link', 'block_flowise_bot'),
        get_string('footer_company_link_desc', 'block_flowise_bot'),
        '',
        PARAM_URL
    ));

    // Advanced settings heading
    $settings->add(new admin_setting_heading(
        'block_flowise_bot/advancedconfig',
        get_string('advancedconfig', 'block_flowise_bot'),
        ''
    ));

    // Custom CSS
    $settings->add(new admin_setting_configtextarea(
        'block_flowise_bot/custom_css',
        get_string('custom_css', 'block_flowise_bot'),
        get_string('custom_css_desc', 'block_flowise_bot'),
        '',
        PARAM_RAW
    ));

    // Custom JavaScript
    $settings->add(new admin_setting_configtextarea(
        'block_flowise_bot/custom_js',
        get_string('custom_js', 'block_flowise_bot'),
        get_string('custom_js_desc', 'block_flowise_bot'),
        '',
        PARAM_RAW
    ));

    // Save Conversations
    $settings->add(new admin_setting_configcheckbox(
        'block_flowise_bot/save_conversations',
        get_string('save_conversations', 'block_flowise_bot'),
        get_string('save_conversations_desc', 'block_flowise_bot'),
        1
    ));

    // Debug Mode
    $settings->add(new admin_setting_configcheckbox(
        'block_flowise_bot/debug_mode',
        get_string('debug_mode', 'block_flowise_bot'),
        get_string('debug_mode_desc', 'block_flowise_bot'),
        0
    ));
}