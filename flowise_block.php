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
 * Block flowise_bot
 *
 * @package    block_flowise_bot
 * @copyright  2025 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_flowise_bot extends block_base {
    /**
     * Initialize the block
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_flowise_bot');
    }

    /**
     * Specialization function to load block settings.
     */
    public function specialization() {
        if (isset($this->config->title)) {
            $this->title = $this->config->title;
        }
    }

    /**
     * Return whether the block has settings.
     *
     * @return boolean true if the block has settings
     */
    public function has_config() {
        return true;
    }

    /**
     * Allows the block to be added multiple times to a single page
     *
     * @return boolean true if the block can be added more than once
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Set where the block should be allowed to be added
     *
     * @return array contexts where the block can be displayed
     */
    public function applicable_formats() {
        return array(
            'all' => true,
            'site' => true,
            'site-index' => true,
            'course-view' => true,
            'course-view-social' => true,
            'mod' => true,
            'my' => true
        );
    }

    /**
     * Get content for the block.
     *
     * @return object $this->content
     */
    public function get_content() {
        global $CFG, $PAGE;

        if ($this->content !== null) {
            return $this->content;
        }

        // Initialize content object
        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        // Get config
        $config = get_config('block_flowise_bot');

        // Check if required settings are configured
        if (empty($config->chatflow_id) || empty($config->api_host)) {
            if (has_capability('moodle/site:config', context_system::instance())) {
                $this->content->text = get_string('configrequired', 'block_flowise_bot');
                $this->content->footer = html_writer::link(
                    new moodle_url('/admin/settings.php', array('section' => 'blocksettingflowise_bot')),
                    get_string('configurebot', 'block_flowise_bot')
                );
            }
            return $this->content;
        }

        // Check if the bot should be displayed based on settings
        if (!$this->should_display_bot()) {
            return $this->content;
        }

        // Add necessary JavaScript
        $this->page->requires->js_call_amd('block_flowise_bot/bot_handler', 'init', array(
            'chatflowId' => $config->chatflow_id,
            'apiHost' => $config->api_host,
            'buttonColor' => $config->button_color,
            'buttonPositionRight' => (int)$config->button_position_right,
            'buttonPositionBottom' => (int)$config->button_position_bottom,
            'buttonSize' => (int)$config->button_size,
            'enableDrag' => (bool)$config->enable_drag,
            'iconColor' => $config->icon_color,
            'customIcon' => $config->custom_icon,
            'windowTitle' => $config->window_title,
            'welcomeMessage' => $config->welcome_message,
            'errorMessage' => $config->error_message,
            'windowHeight' => (int)$config->window_height,
            'windowWidth' => (int)$config->window_width,
            'windowBackgroundColor' => $config->window_background_color,
            'fontSize' => (int)$config->font_size,
            'botMessageBgColor' => $config->bot_message_bg_color,
            'botMessageTextColor' => $config->bot_message_text_color,
            'userMessageBgColor' => $config->user_message_bg_color,
            'userMessageTextColor' => $config->user_message_text_color,
            'inputPlaceholder' => $config->input_placeholder,
            'inputBgColor' => $config->input_bg_color,
            'inputTextColor' => $config->input_text_color,
            'inputSendButtonColor' => $config->input_send_button_color,
            'showTooltip' => (bool)$config->show_tooltip,
            'tooltipMessage' => $config->tooltip_message,
            'tooltipBgColor' => $config->tooltip_bg_color,
            'tooltipTextColor' => $config->tooltip_text_color,
            'autoOpen' => (bool)$config->auto_open,
            'autoOpenDelay' => (int)$config->auto_open_delay,
            'autoOpenMobile' => (bool)$config->auto_open_mobile,
            'starterPrompts' => !empty($config->starter_prompts) ? explode("\n", $config->starter_prompts) : [],
            'footerText' => $config->footer_text,
            'footerTextColor' => $config->footer_text_color,
            'footerCompany' => $config->footer_company,
            'footerCompanyLink' => $config->footer_company_link,
            'sessionId' => $this->generate_session_id(),
            'contextId' => $PAGE->context->id,
            'courseId' => $PAGE->course->id,
            'userId' => $PAGE->user->id
        ));

        // Custom CSS
        if (!empty($config->custom_css)) {
            $PAGE->requires->css(new moodle_url('/blocks/flowise_bot/assets/css/custom.css'));
        }

        // Content can be an empty div as the chatbot will be added via JavaScript
        $this->content->text = '<div id="flowise-bot-container" class="block_flowise_bot_container"></div>';

        return $this->content;
    }

    /**
     * Check if the bot should be displayed based on settings and context
     *
     * @return boolean
     */
    private function should_display_bot() {
        global $PAGE, $USER;
        
        $config = get_config('block_flowise_bot');

        // Hide on mobile if configured
        if (!empty($config->hide_on_mobile) && $PAGE->devicetypeinuse == 'mobile') {
            return false;
        }

        // Only show for logged-in users if configured
        if (!empty($config->show_for_logged_in) && !isloggedin()) {
            return false;
        }

        // Check user roles if specific roles are configured
        if (!empty($config->show_for_roles) && isloggedin()) {
            $roles = explode(',', $config->show_for_roles);
            $hasrole = false;
            
            foreach ($roles as $roleid) {
                if (user_has_role_assignment($USER->id, trim($roleid), $PAGE->context->id)) {
                    $hasrole = true;
                    break;
                }
            }
            
            if (!$hasrole) {
                return false;
            }
        }

        // Check display pages
        $display_pages = !empty($config->display_pages) ? explode(',', $config->display_pages) : array('all');
        
        if (in_array('all', $display_pages)) {
            return true;
        }

        $pagetype = $PAGE->pagetype;
        
        if (in_array('site-index', $display_pages) && $pagetype == 'site-index') {
            return true;
        }

        if (in_array('course-view', $display_pages) && substr($pagetype, 0, 11) == 'course-view') {
            return true;
        }

        if (in_array('my', $display_pages) && $pagetype == 'my-index') {
            return true;
        }

        if (in_array('mod', $display_pages) && substr($pagetype, 0, 4) == 'mod-') {
            return true;
        }

        return false;
    }

    /**
     * Generate a unique session ID for tracking conversations
     *
     * @return string
     */
    private function generate_session_id() {
        if (isset($_COOKIE['flowise_bot_session_id'])) {
            return $_COOKIE['flowise_bot_session_id'];
        }
        
        $sessionid = substr(str_shuffle(MD5(microtime())), 0, 20);
        setcookie('flowise_bot_session_id', $sessionid, time() + 86400, '/');
        
        return $sessionid;
    }
}
