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
 * Installation hook for IGIS Flowise Bot plugin
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Custom installation procedure
 *
 * @return bool
 */
function xmldb_local_igisflowise_install() {
    global $CFG, $DB;

    // Set default configuration
    $config = new stdClass();
    $config->enabled = 1;
    $config->chatflow_id = '';
    $config->api_host = '';
    $config->button_color = '#3B81F6';
    $config->button_position_right = 20;
    $config->button_position_bottom = 20;
    $config->button_size = 48;
    $config->enable_drag = 1;
    $config->icon_color = '#FFFFFF';
    $config->custom_icon = 'https://raw.githubusercontent.com/walkxcode/dashboard-icons/main/svg/google-messages.svg';
    $config->window_title = 'IGIS Bot';
    $config->welcome_message = get_string('default_welcome_message', 'local_igisflowise');
    $config->error_message = get_string('default_error_message', 'local_igisflowise');
    $config->window_height = 700;
    $config->window_width = 400;
    $config->window_background_color = '#FFFFFF';
    $config->font_size = 16;
    $config->bot_message_bg_color = '#f7f8ff';
    $config->bot_message_text_color = '#303235';
    $config->bot_avatar_enabled = 1;
    $config->bot_avatar_src = '';
    $config->user_message_bg_color = '#3B81F6';
    $config->user_message_text_color = '#FFFFFF';
    $config->user_avatar_enabled = 1;
    $config->user_avatar_src = '';
    $config->input_placeholder = get_string('default_input_placeholder', 'local_igisflowise');
    $config->input_bg_color = '#FFFFFF';
    $config->input_text_color = '#303235';
    $config->input_send_button_color = '#3B81F6';
    $config->max_chars = 1000;
    $config->display_pages = 'all';
    $config->auto_open = 0;
    $config->auto_open_delay = 2;
    $config->auto_open_mobile = 0;
    $config->show_for_logged_in = 0;
    $config->show_for_roles = '';
    $config->hide_on_mobile = 0;
    $config->show_tooltip = 1;
    $config->tooltip_message = get_string('default_tooltip_message', 'local_igisflowise');
    $config->tooltip_bg_color = '#000000';
    $config->tooltip_text_color = '#FFFFFF';
    $config->tooltip_font_size = 16;
    $config->starter_prompts = "What is a bot?\nWho are you?";
    $config->enable_send_sound = 1;
    $config->send_sound_url = '';
    $config->enable_receive_sound = 1;
    $config->receive_sound_url = '';
    $config->footer_text = 'Powered by IGIS Bot';
    $config->footer_text_color = '#303235';
    $config->footer_company = 'InfraestructuraGIS';
    $config->footer_company_link = 'https://www.infraestructuragis.com/';
    $config->show_disclaimer = 0;
    $config->disclaimer_title = 'Disclaimer';
    $config->disclaimer_message = '';
    $config->disclaimer_button_text = 'Start Chatting';
    $config->disclaimer_button_color = '#3b82f6';
    $config->disclaimer_text_color = '#000000';
    $config->disclaimer_bg_color = '#FFFFFF';
    $config->disclaimer_overlay_color = 'rgba(0, 0, 0, 0.4)';
    $config->custom_css = '';
    $config->custom_js = '';
    $config->custom_headers = '';
    $config->rate_limiting = 60;
    $config->session_timeout = 30;
    $config->debug_mode = 0;
    $config->save_conversations = 0;

    // Save config
    foreach ($config as $name => $value) {
        set_config($name, $value, 'local_igisflowise');
    }

    return true;
}
