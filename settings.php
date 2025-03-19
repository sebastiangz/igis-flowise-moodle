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
 * Plugin settings and presets.
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/igisflowise/lib.php');

if ($hassiteconfig) {
    // Create settings page
    $settings = new admin_settingpage('local_igisflowise', get_string('pluginname', 'local_igisflowise'));
    $ADMIN->add('localplugins', $settings);

    // General section
    $settings->add(new admin_setting_heading('local_igisflowise/general_settings',
        get_string('general_settings', 'local_igisflowise'),
        get_string('general_settings_desc', 'local_igisflowise')));

    // Enable/disable bot
    $settings->add(new admin_setting_configcheckbox('local_igisflowise/enabled',
        get_string('enabled', 'local_igisflowise'),
        get_string('enabled_desc', 'local_igisflowise'),
        1));

    // Flowise configuration
    $settings->add(new admin_setting_configtext('local_igisflowise/chatflow_id',
        get_string('chatflow_id', 'local_igisflowise'),
        get_string('chatflow_id_desc', 'local_igisflowise'),
        '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('local_igisflowise/api_host',
        get_string('api_host', 'local_igisflowise'),
        get_string('api_host_desc', 'local_igisflowise'),
        '', PARAM_URL));

    // Appearance section
    $settings->add(new admin_setting_heading('local_igisflowise/appearance_settings',
        get_string('appearance_settings', 'local_igisflowise'),
        get_string('appearance_settings_desc', 'local_igisflowise')));

    // Button settings
    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/button_color',
        get_string('button_color', 'local_igisflowise'),
        get_string('button_color_desc', 'local_igisflowise'),
        '#3B81F6'));

    $settings->add(new admin_setting_configtext('local_igisflowise/button_position_right',
        get_string('button_position_right', 'local_igisflowise'),
        get_string('button_position_right_desc', 'local_igisflowise'),
        '20', PARAM_INT));

    $settings->add(new admin_setting_configtext('local_igisflowise/button_position_bottom',
        get_string('button_position_bottom', 'local_igisflowise'),
        get_string('button_position_bottom_desc', 'local_igisflowise'),
        '20', PARAM_INT));

    $settings->add(new admin_setting_configtext('local_igisflowise/button_size',
        get_string('button_size', 'local_igisflowise'),
        get_string('button_size_desc', 'local_igisflowise'),
        '48', PARAM_INT));

    $settings->add(new admin_setting_configcheckbox('local_igisflowise/enable_drag',
        get_string('enable_drag', 'local_igisflowise'),
        get_string('enable_drag_desc', 'local_igisflowise'),
        1));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/icon_color',
        get_string('icon_color', 'local_igisflowise'),
        get_string('icon_color_desc', 'local_igisflowise'),
        '#FFFFFF'));

    $settings->add(new admin_setting_configtext('local_igisflowise/custom_icon',
        get_string('custom_icon', 'local_igisflowise'),
        get_string('custom_icon_desc', 'local_igisflowise'),
        'https://raw.githubusercontent.com/walkxcode/dashboard-icons/main/svg/google-messages.svg', PARAM_URL));

    // Chat window settings
    $settings->add(new admin_setting_configtext('local_igisflowise/window_title',
        get_string('window_title', 'local_igisflowise'),
        get_string('window_title_desc', 'local_igisflowise'),
        'IGIS Bot', PARAM_TEXT));

    $settings->add(new admin_setting_configtextarea('local_igisflowise/welcome_message',
        get_string('welcome_message', 'local_igisflowise'),
        get_string('welcome_message_desc', 'local_igisflowise'),
        get_string('default_welcome_message', 'local_igisflowise'), PARAM_TEXT));

    $settings->add(new admin_setting_configtextarea('local_igisflowise/error_message',
        get_string('error_message', 'local_igisflowise'),
        get_string('error_message_desc', 'local_igisflowise'),
        get_string('default_error_message', 'local_igisflowise'), PARAM_TEXT));

    $settings->add(new admin_setting_configtext('local_igisflowise/window_height',
        get_string('window_height', 'local_igisflowise'),
        get_string('window_height_desc', 'local_igisflowise'),
        '700', PARAM_INT));

    $settings->add(new admin_setting_configtext('local_igisflowise/window_width',
        get_string('window_width', 'local_igisflowise'),
        get_string('window_width_desc', 'local_igisflowise'),
        '400', PARAM_INT));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/window_background_color',
        get_string('window_background_color', 'local_igisflowise'),
        get_string('window_background_color_desc', 'local_igisflowise'),
        '#FFFFFF'));

    $settings->add(new admin_setting_configtext('local_igisflowise/font_size',
        get_string('font_size', 'local_igisflowise'),
        get_string('font_size_desc', 'local_igisflowise'),
        '16', PARAM_INT));

    // Messages section
    $settings->add(new admin_setting_heading('local_igisflowise/messages_settings',
        get_string('messages_settings', 'local_igisflowise'),
        get_string('messages_settings_desc', 'local_igisflowise')));

    // Bot message settings
    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/bot_message_bg_color',
        get_string('bot_message_bg_color', 'local_igisflowise'),
        get_string('bot_message_bg_color_desc', 'local_igisflowise'),
        '#f7f8ff'));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/bot_message_text_color',
        get_string('bot_message_text_color', 'local_igisflowise'),
        get_string('bot_message_text_color_desc', 'local_igisflowise'),
        '#303235'));

    $settings->add(new admin_setting_configcheckbox('local_igisflowise/bot_avatar_enabled',
        get_string('bot_avatar_enabled', 'local_igisflowise'),
        get_string('bot_avatar_enabled_desc', 'local_igisflowise'),
        1));

    $settings->add(new admin_setting_configtext('local_igisflowise/bot_avatar_src',
        get_string('bot_avatar_src', 'local_igisflowise'),
        get_string('bot_avatar_src_desc', 'local_igisflowise'),
        '', PARAM_URL));

    // User message settings
    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/user_message_bg_color',
        get_string('user_message_bg_color', 'local_igisflowise'),
        get_string('user_message_bg_color_desc', 'local_igisflowise'),
        '#3B81F6'));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/user_message_text_color',
        get_string('user_message_text_color', 'local_igisflowise'),
        get_string('user_message_text_color_desc', 'local_igisflowise'),
        '#FFFFFF'));

    $settings->add(new admin_setting_configcheckbox('local_igisflowise/user_avatar_enabled',
        get_string('user_avatar_enabled', 'local_igisflowise'),
        get_string('user_avatar_enabled_desc', 'local_igisflowise'),
        1));

    $settings->add(new admin_setting_configtext('local_igisflowise/user_avatar_src',
        get_string('user_avatar_src', 'local_igisflowise'),
        get_string('user_avatar_src_desc', 'local_igisflowise'),
        '', PARAM_URL));

    // Input settings
    $settings->add(new admin_setting_configtext('local_igisflowise/input_placeholder',
        get_string('input_placeholder', 'local_igisflowise'),
        get_string('input_placeholder_desc', 'local_igisflowise'),
        get_string('default_input_placeholder', 'local_igisflowise'), PARAM_TEXT));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/input_bg_color',
        get_string('input_bg_color', 'local_igisflowise'),
        get_string('input_bg_color_desc', 'local_igisflowise'),
        '#FFFFFF'));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/input_text_color',
        get_string('input_text_color', 'local_igisflowise'),
        get_string('input_text_color_desc', 'local_igisflowise'),
        '#303235'));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/input_send_button_color',
        get_string('input_send_button_color', 'local_igisflowise'),
        get_string('input_send_button_color_desc', 'local_igisflowise'),
        '#3B81F6'));

    $settings->add(new admin_setting_configtext('local_igisflowise/max_chars',
        get_string('max_chars', 'local_igisflowise'),
        get_string('max_chars_desc', 'local_igisflowise'),
        '1000', PARAM_INT));

    // Display section
    $settings->add(new admin_setting_heading('local_igisflowise/display_settings',
        get_string('display_settings', 'local_igisflowise'),
        get_string('display_settings_desc', 'local_igisflowise')));

    // Display options
    $pagetypes = local_igisflowise_get_page_types();
    $settings->add(new admin_setting_configmulticheckbox('local_igisflowise/display_pages',
        get_string('display_pages', 'local_igisflowise'),
        get_string('display_pages_desc', 'local_igisflowise'),
        array('all' => 1), $pagetypes));

    $settings->add(new admin_setting_configcheckbox('local_igisflowise/auto_open',
        get_string('auto_open', 'local_igisflowise'),
        get_string('auto_open_desc', 'local_igisflowise'),
        0));

    $settings->add(new admin_setting_configtext('local_igisflowise/auto_open_delay',
        get_string('auto_open_delay', 'local_igisflowise'),
        get_string('auto_open_delay_desc', 'local_igisflowise'),
        '2', PARAM_INT));

    $settings->add(new admin_setting_configcheckbox('local_igisflowise/auto_open_mobile',
        get_string('auto_open_mobile', 'local_igisflowise'),
        get_string('auto_open_mobile_desc', 'local_igisflowise'),
        0));

    $settings->add(new admin_setting_configcheckbox('local_igisflowise/show_for_logged_in',
        get_string('show_for_logged_in', 'local_igisflowise'),
        get_string('show_for_logged_in_desc', 'local_igisflowise'),
        0));

    $roles = local_igisflowise_get_roles();
    $settings->add(new admin_setting_configmulticheckbox('local_igisflowise/show_for_roles',
        get_string('show_for_roles', 'local_igisflowise'),
        get_string('show_for_roles_desc', 'local_igisflowise'),
        array(), $roles));

    $settings->add(new admin_setting_configcheckbox('local_igisflowise/hide_on_mobile',
        get_string('hide_on_mobile', 'local_igisflowise'),
        get_string('hide_on_mobile_desc', 'local_igisflowise'),
        0));

    // Tooltip settings
    $settings->add(new admin_setting_configcheckbox('local_igisflowise/show_tooltip',
        get_string('show_tooltip', 'local_igisflowise'),
        get_string('show_tooltip_desc', 'local_igisflowise'),
        1));

    $settings->add(new admin_setting_configtext('local_igisflowise/tooltip_message',
        get_string('tooltip_message', 'local_igisflowise'),
        get_string('tooltip_message_desc', 'local_igisflowise'),
        get_string('default_tooltip_message', 'local_igisflowise'), PARAM_TEXT));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/tooltip_bg_color',
        get_string('tooltip_bg_color', 'local_igisflowise'),
        get_string('tooltip_bg_color_desc', 'local_igisflowise'),
        '#000000'));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/tooltip_text_color',
        get_string('tooltip_text_color', 'local_igisflowise'),
        get_string('tooltip_text_color_desc', 'local_igisflowise'),
        '#FFFFFF'));

    $settings->add(new admin_setting_configtext('local_igisflowise/tooltip_font_size',
        get_string('tooltip_font_size', 'local_igisflowise'),
        get_string('tooltip_font_size_desc', 'local_igisflowise'),
        '16', PARAM_INT));

    // Starter prompts
    $settings->add(new admin_setting_configtextarea('local_igisflowise/starter_prompts',
        get_string('starter_prompts', 'local_igisflowise'),
        get_string('starter_prompts_desc', 'local_igisflowise'),
        "What is a bot?\nWho are you?", PARAM_TEXT));

    // Sound settings
    $settings->add(new admin_setting_configcheckbox('local_igisflowise/enable_send_sound',
        get_string('enable_send_sound', 'local_igisflowise'),
        get_string('enable_send_sound_desc', 'local_igisflowise'),
        1));

    $settings->add(new admin_setting_configtext('local_igisflowise/send_sound_url',
        get_string('send_sound_url', 'local_igisflowise'),
        get_string('send_sound_url_desc', 'local_igisflowise'),
        '', PARAM_URL));

    $settings->add(new admin_setting_configcheckbox('local_igisflowise/enable_receive_sound',
        get_string('enable_receive_sound', 'local_igisflowise'),
        get_string('enable_receive_sound_desc', 'local_igisflowise'),
        1));

    $settings->add(new admin_setting_configtext('local_igisflowise/receive_sound_url',
        get_string('receive_sound_url', 'local_igisflowise'),
        get_string('receive_sound_url_desc', 'local_igisflowise'),
        '', PARAM_URL));

    // Footer settings
    $settings->add(new admin_setting_configtext('local_igisflowise/footer_text',
        get_string('footer_text', 'local_igisflowise'),
        get_string('footer_text_desc', 'local_igisflowise'),
        'Powered by IGIS Bot', PARAM_TEXT));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/footer_text_color',
        get_string('footer_text_color', 'local_igisflowise'),
        get_string('footer_text_color_desc', 'local_igisflowise'),
        '#303235'));

    $settings->add(new admin_setting_configtext('local_igisflowise/footer_company',
        get_string('footer_company', 'local_igisflowise'),
        get_string('footer_company_desc', 'local_igisflowise'),
        'InfraestructuraGIS', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('local_igisflowise/footer_company_link',
        get_string('footer_company_link', 'local_igisflowise'),
        get_string('footer_company_link_desc', 'local_igisflowise'),
        'https://www.infraestructuragis.com/', PARAM_URL));

    // Disclaimer settings
    $settings->add(new admin_setting_configcheckbox('local_igisflowise/show_disclaimer',
        get_string('show_disclaimer', 'local_igisflowise'),
        get_string('show_disclaimer_desc', 'local_igisflowise'),
        0));

    $settings->add(new admin_setting_configtext('local_igisflowise/disclaimer_title',
        get_string('disclaimer_title', 'local_igisflowise'),
        get_string('disclaimer_title_desc', 'local_igisflowise'),
        'Disclaimer', PARAM_TEXT));

    $settings->add(new admin_setting_configtextarea('local_igisflowise/disclaimer_message',
        get_string('disclaimer_message', 'local_igisflowise'),
        get_string('disclaimer_message_desc', 'local_igisflowise'),
        '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('local_igisflowise/disclaimer_button_text',
        get_string('disclaimer_button_text', 'local_igisflowise'),
        get_string('disclaimer_button_text_desc', 'local_igisflowise'),
        'Start Chatting', PARAM_TEXT));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/disclaimer_button_color',
        get_string('disclaimer_button_color', 'local_igisflowise'),
        get_string('disclaimer_button_color_desc', 'local_igisflowise'),
        '#3b82f6'));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/disclaimer_text_color',
        get_string('disclaimer_text_color', 'local_igisflowise'),
        get_string('disclaimer_text_color_desc', 'local_igisflowise'),
        '#000000'));

    $settings->add(new admin_setting_configcolourpicker('local_igisflowise/disclaimer_bg_color',
        get_string('disclaimer_bg_color', 'local_igisflowise'),
        get_string('disclaimer_bg_color_desc', 'local_igisflowise'),
        '#FFFFFF'));

    $settings->add(new admin_setting_configtext('local_igisflowise/disclaimer_overlay_color',
        get_string('disclaimer_overlay_color', 'local_igisflowise'),
        get_string('disclaimer_overlay_color_desc', 'local_igisflowise'),
        'rgba(0, 0, 0, 0.4)', PARAM_TEXT));

    // Advanced settings
    $settings->add(new admin_setting_heading('local_igisflowise/advanced_settings',
        get_string('advanced_settings', 'local_igisflowise'),
        get_string('advanced_settings_desc', 'local_igisflowise')));

    $settings->add(new admin_setting_configtextarea('local_igisflowise/custom_css',
        get_string('custom_css', 'local_igisflowise'),
        get_string('custom_css_desc', 'local_igisflowise'),
        '', PARAM_RAW));

    $settings->add(new admin_setting_configtextarea('local_igisflowise/custom_js',
        get_string('custom_js', 'local_igisflowise'),
        get_string('custom_js_desc', 'local_igisflowise'),
        '', PARAM_RAW));

    $settings->add(new admin_setting_configtextarea('local_igisflowise/custom_headers',
        get_string('custom_headers', 'local_igisflowise'),
        get_string('custom_headers_desc', 'local_igisflowise'),
        '', PARAM_RAW));

    $settings->add(new admin_setting_configtext('local_igisflowise/rate_limiting',
        get_string('rate_limiting', 'local_igisflowise'),
        get_string('rate_limiting_desc', 'local_igisflowise'),
        '60', PARAM_INT));

    $settings->add(new admin_setting_configtext('local_igisflowise/session_timeout',
        get_string('session_timeout', 'local_igisflowise'),
        get_string('session_timeout_desc', 'local_igisflowise'),
        '30', PARAM_INT));

    $settings->add(new admin_setting_configcheckbox('local_igisflowise/debug_mode',
        get_string('debug_mode', 'local_igisflowise'),
        get_string('debug_mode_desc', 'local_igisflowise'),
        0));

    $settings->add(new admin_setting_configcheckbox('local_igisflowise/save_conversations',
        get_string('save_conversations', 'local_igisflowise'),
        get_string('save_conversations_desc', 'local_igisflowise'),
        0));
}