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
 * Library functions for local_igisflowise
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Function to add the bot to Moodle's footer.
 * This is called automatically by Moodle's output system.
 *
 * @return string HTML for the bot
 */
function local_igisflowise_before_footer() {
    global $PAGE, $DB, $USER, $COURSE;

    // Get the plugin configuration
    $config = get_config('local_igisflowise');

    // Check if the bot is enabled
    if (empty($config->enabled) || $config->enabled == 0) {
        return '';
    }

    // Check if the bot should be displayed on this page
    if (!local_igisflowise_should_display_bot($config)) {
        return '';
    }

    // Include the chatbot script
    return local_igisflowise_render_bot($config);
}

/**
 * Hook for extend_navigation. This doesn't need to do anything but
 * is required to make Moodle recognize our before_footer function.
 */
function local_igisflowise_extend_navigation(global_navigation $navigation) {
    // We don't need to do anything here
}

/**
 * Determines if the chatbot should be displayed on the current page.
 *
 * @param stdClass $config The plugin configuration.
 * @return bool True if the bot should be displayed, false otherwise.
 */
function local_igisflowise_should_display_bot($config) {
    global $PAGE, $COURSE, $USER;

    // Check mobile display settings
    if (!empty($config->hide_on_mobile) && $config->hide_on_mobile == 1) {
        $useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) 
            || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            return false;
        }
    }

    // Check user login requirements
    if (!empty($config->show_for_logged_in) && $config->show_for_logged_in == 1) {
        if (!isloggedin() || isguestuser()) {
            return false;
        }
    }

    // Check user roles if specified
    if (!empty($config->show_for_roles)) {
        $show_for_roles = is_array($config->show_for_roles) ? $config->show_for_roles : explode(',', $config->show_for_roles);
        
        if (isloggedin() && !is_siteadmin()) {
            $context = context_course::instance($COURSE->id);
            $userroles = get_user_roles($context);
            $has_required_role = false;
            
            foreach ($userroles as $role) {
                if (in_array($role->shortname, $show_for_roles)) {
                    $has_required_role = true;
                    break;
                }
            }
            
            if (!$has_required_role) {
                return false;
            }
        }
    }

    // Check display pages settings
    $current_page = $PAGE->pagetype;
    if (!empty($config->display_pages)) {
        $display_pages = is_array($config->display_pages) ? $config->display_pages : explode(',', $config->display_pages);
        
        // If 'all' is selected, display on all pages
        if (in_array('all', $display_pages)) {
            return true;
        }
        
        // Check for specific page types
        if (in_array('site-index', $display_pages) && $current_page == 'site-index') {
            return true; // Front page
        }
        
        if (in_array('my-index', $display_pages) && $current_page == 'my-index') {
            return true; // Dashboard
        }
        
        if (in_array('course-view', $display_pages) && strpos($current_page, 'course-view') === 0) {
            return true; // Course pages
        }
        
        if (in_array('mod-page', $display_pages) && strpos($current_page, 'mod-') === 0) {
            return true; // Module pages
        }
        
        if (in_array('user-profile', $display_pages) && strpos($current_page, 'user-profile') === 0) {
            return true; // User profile pages
        }
        
        if (in_array('login-index', $display_pages) && $current_page == 'login-index') {
            return true; // Login page
        }
        
        return false;
    }

    // Default to display if no specific settings contradict
    return true;
}

/**
 * Renders the Flowise Bot script and configuration.
 *
 * @param stdClass $config The plugin configuration.
 * @return string HTML and JavaScript code to render the bot.
 */
function local_igisflowise_render_bot($config) {
    global $USER, $DB, $CFG, $PAGE;

    // Check for required configuration
    if (empty($config->chatflow_id) || empty($config->api_host)) {
        if (!empty($config->debug_mode) && $config->debug_mode == 1) {
            return '<!-- IGIS Flowise Bot: Incomplete configuration. Please set chatflow_id and api_host -->';
        }
        return '';
    }

    // Prepare starter prompts if defined
    $starter_prompts = array();
    if (!empty($config->starter_prompts)) {
        $starter_prompts = array_map('trim', explode("\n", $config->starter_prompts));
    }

    // Create bot configuration
    $bot_config = array(
        'chatflowid' => $config->chatflow_id,
        'apiHost' => $config->api_host,
        'theme' => array(
            'button' => array(
                'backgroundColor' => $config->button_color,
                'right' => intval($config->button_position_right),
                'bottom' => intval($config->button_position_bottom),
                'size' => intval($config->button_size),
                'iconColor' => $config->icon_color,
                'customIconSrc' => $config->custom_icon,
                'dragable' => !empty($config->enable_drag) && $config->enable_drag == 1
            ),
            'chatWindow' => array(
                'welcomeMessage' => $config->welcome_message,
                'backgroundColor' => $config->window_background_color,
                'height' => intval($config->window_height),
                'width' => intval($config->window_width),
                'fontSize' => intval($config->font_size),
                'title' => $config->window_title,
                'errorMessage' => $config->error_message
            ),
            'userMessage' => array(
                'backgroundColor' => $config->user_message_bg_color,
                'textColor' => $config->user_message_text_color,
                'showAvatar' => !empty($config->user_avatar_enabled) && $config->user_avatar_enabled == 1,
                'avatarSrc' => $config->user_avatar_src
            ),
            'botMessage' => array(
                'backgroundColor' => $config->bot_message_bg_color,
                'textColor' => $config->bot_message_text_color,
                'showAvatar' => !empty($config->bot_avatar_enabled) && $config->bot_avatar_enabled == 1,
                'avatarSrc' => $config->bot_avatar_src
            ),
            'textInput' => array(
                'placeholder' => $config->input_placeholder,
                'backgroundColor' => $config->input_bg_color,
                'textColor' => $config->input_text_color,
                'sendButtonColor' => $config->input_send_button_color,
                'maxInputChars' => intval($config->max_chars)
            ),
            'tooltip' => array(
                'showTooltip' => !empty($config->show_tooltip) && $config->show_tooltip == 1,
                'tooltipMessage' => $config->tooltip_message,
                'backgroundColor' => $config->tooltip_bg_color,
                'textColor' => $config->tooltip_text_color,
                'fontSize' => intval($config->tooltip_font_size)
            )
        ),
        // Añadimos información para los manejadores de eventos
        'saveConversations' => !empty($config->save_conversations) && $config->save_conversations == 1,
        'wwwroot' => $CFG->wwwroot,
        'sesskey' => sesskey(),
        'debugMode' => !empty($config->debug_mode) && $config->debug_mode == 1
    );

    // Add autoWindowOpen if enabled
    if (!empty($config->auto_open) && $config->auto_open == 1) {
        $bot_config['theme']['button']['autoWindowOpen'] = array(
            'autoOpen' => true,
            'openDelay' => intval($config->auto_open_delay),
            'autoOpenOnMobile' => !empty($config->auto_open_mobile) && $config->auto_open_mobile == 1
        );
    }

    // Add starter prompts if any
    if (!empty($starter_prompts)) {
        $bot_config['theme']['chatWindow']['starterPrompts'] = $starter_prompts;
    }

    // Add footer if there is footer text
    if (!empty($config->footer_text)) {
        $bot_config['theme']['chatWindow']['footer'] = array(
            'textColor' => $config->footer_text_color,
            'text' => $config->footer_text
        );
        
        if (!empty($config->footer_company)) {
            $bot_config['theme']['chatWindow']['footer']['company'] = $config->footer_company;
            $bot_config['theme']['chatWindow']['footer']['companyLink'] = $config->footer_company_link;
        }
    }

    // Add disclaimer if enabled
    if (!empty($config->show_disclaimer) && $config->show_disclaimer == 1) {
        $bot_config['theme']['chatWindow']['disclaimer'] = array(
            'show' => true,
            'title' => $config->disclaimer_title,
            'message' => $config->disclaimer_message,
            'buttonText' => $config->disclaimer_button_text,
            'buttonColor' => $config->disclaimer_button_color,
            'textColor' => $config->disclaimer_text_color,
            'backgroundColor' => $config->disclaimer_bg_color,
            'blurredBackgroundColor' => $config->disclaimer_overlay_color
        );
    }

    // Configure sound settings
    if (!empty($config->enable_send_sound) && $config->enable_send_sound == 1) {
        $bot_config['theme']['textInput']['sendMessageSound'] = true;
        if (!empty($config->send_sound_url)) {
            $bot_config['theme']['textInput']['sendSoundLocation'] = $config->send_sound_url;
        }
    }

    if (!empty($config->enable_receive_sound) && $config->enable_receive_sound == 1) {
        $bot_config['theme']['textInput']['receiveMessageSound'] = true;
        if (!empty($config->receive_sound_url)) {
            $bot_config['theme']['textInput']['receiveSoundLocation'] = $config->receive_sound_url;
        }
    }

    // Add custom headers if defined
    if (!empty($config->custom_headers)) {
        $headers = json_decode($config->custom_headers, true);
        if (is_array($headers)) {
            $bot_config['headers'] = $headers;
        }
    }

    // El ID único para este chatbot en la página
    $bot_id = 'igis-flowise-bot-' . uniqid();
    
    // Agrega CSS personalizado si está configurado
    $custom_css = '';
    if (!empty($config->custom_css)) {
        $custom_css = '<style type="text/css">' . $config->custom_css . '</style>';
    }
    
    // Cargar el módulo JavaScript del bot utilizando el sistema AMD de Moodle
    $PAGE->requires->js_call_amd('local_igisflowise/bot_launcher', 'init', array($bot_config));
    
    // Si hay JavaScript personalizado, agregarlo también
    if (!empty($config->custom_js)) {
        $PAGE->requires->js_init_code($config->custom_js);
    }
    
    // Retorna el contenedor para el bot y cualquier CSS personalizado
    return '<div id="' . $bot_id . '" class="igis-flowise-bot-container"></div>' . $custom_css;
}

/**
 * Get all available user roles in Moodle.
 *
 * @return array Array of role ID => role name pairs.
 */
function local_igisflowise_get_roles() {
    global $DB;
    
    $roles = $DB->get_records('role', null, 'sortorder ASC', 'id, shortname, name, description');
    $role_options = array();
    
    foreach ($roles as $role) {
        $role_options[$role->shortname] = role_get_name($role);
    }
    
    return $role_options;
}

/**
 * Get all available page types for display settings.
 *
 * @return array Array of page type => display name pairs.
 */
function local_igisflowise_get_page_types() {
    return array(
        'all' => get_string('all_pages', 'local_igisflowise'),
        'site-index' => get_string('frontpage', 'local_igisflowise'),
        'my-index' => get_string('dashboard', 'local_igisflowise'),
        'course-view' => get_string('course_pages', 'local_igisflowise'),
        'mod-page' => get_string('module_pages', 'local_igisflowise'),
        'user-profile' => get_string('user_profile', 'local_igisflowise'),
        'login-index' => get_string('login_page', 'local_igisflowise')
    );
}