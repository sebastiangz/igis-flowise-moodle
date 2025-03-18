/**
 * JavaScript module for the Flowise Bot integration
 *
 * @module     block_flowise_bot/bot_handler
 * @copyright  2025 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {
    /**
     * Module initialization function
     *
     * @param {Object} options Configuration options from the server
     */
    var init = function(options) {
        // Load Flowise embed script
        loadFlowiseScript().then(function() {
            // Initialize the chatbot with the provided options
            initializeChatbot(options);
            
            // Set up event listeners for conversation tracking
            if (options.save_conversations) {
                setupEventListeners(options);
            }
        }).catch(function(error) {
            console.error('Failed to load Flowise script:', error);
            if (options.debug_mode) {
                Notification.exception(error);
            }
        });
    };

    /**
     * Load the Flowise script from CDN
     * 
     * @return {Promise} A promise that resolves when the script is loaded
     */
    var loadFlowiseScript = function() {
        return new Promise(function(resolve, reject) {
            var script = document.createElement('script');
            script.type = 'module';
            script.src = 'https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js';
            script.onload = function() {
                resolve();
            };
            script.onerror = function() {
                reject(new Error('Failed to load Flowise script'));
            };
            document.body.appendChild(script);
        });
    };

    /**
     * Initialize the Flowise chatbot with the provided options
     * 
     * @param {Object} options Configuration options
     */
    var initializeChatbot = function(options) {
        // Prepare bot configuration based on Moodle settings
        var botConfig = {
            chatflowid: options.chatflowId,
            apiHost: options.apiHost,
            theme: {
                button: {
                    backgroundColor: options.buttonColor,
                    right: options.buttonPositionRight,
                    bottom: options.buttonPositionBottom,
                    size: options.buttonSize,
                    iconColor: options.iconColor,
                    customIconSrc: options.customIcon,
                    dragable: options.enableDrag
                },
                chatWindow: {
                    welcomeMessage: options.welcomeMessage,
                    backgroundColor: options.windowBackgroundColor,
                    height: options.windowHeight,
                    width: options.windowWidth,
                    fontSize: options.fontSize,
                    title: options.windowTitle,
                    errorMessage: options.errorMessage
                },
                userMessage: {
                    backgroundColor: options.userMessageBgColor,
                    textColor: options.userMessageTextColor
                },
                botMessage: {
                    backgroundColor: options.botMessageBgColor,
                    textColor: options.botMessageTextColor
                },
                textInput: {
                    placeholder: options.inputPlaceholder,
                    backgroundColor: options.inputBgColor,
                    textColor: options.inputTextColor,
                    sendButtonColor: options.inputSendButtonColor
                }
            }
        };

        // Add tooltip if enabled
        if (options.showTooltip) {
            botConfig.theme.tooltip = {
                showTooltip: true,
                tooltipMessage: options.tooltipMessage,
                backgroundColor: options.tooltipBgColor,
                textColor: options.tooltipTextColor
            };
        }

        // Add auto open if enabled
        if (options.autoOpen) {
            botConfig.theme.button.autoWindowOpen = {
                autoOpen: true,
                openDelay: options.autoOpenDelay,
                autoOpenOnMobile: options.autoOpenMobile
            };
        }

        // Add starter prompts if defined
        if (options.starterPrompts && options.starterPrompts.length > 0) {
            botConfig.theme.chatWindow.starterPrompts = options.starterPrompts;
        }

        // Add footer if defined
        if (options.footerText) {
            botConfig.theme.chatWindow.footer = {
                textColor: options.footerTextColor,
                text: options.footerText
            };
            
            if (options.footerCompany) {
                botConfig.theme.chatWindow.footer.company = options.footerCompany;
                botConfig.theme.chatWindow.footer.companyLink = options.footerCompanyLink;
            }
        }

        // Add custom headers with Moodle-specific information
        botConfig.headers = {
            'X-Moodle-User-Id': options.userId,
            'X-Moodle-Course-Id': options.courseId,
            'X-Moodle-Context-Id': options.contextId,
            'X-Moodle-Session-Id': options.sessionId
        };

        // Initialize the chatbot
        if (typeof window.Chatbot !== 'undefined') {
            window.Chatbot.init(botConfig);
        } else {
            console.error('Flowise Chatbot library not loaded');
        }
    };

    /**
     * Set up event listeners for conversation tracking
     * 
     * @param {Object} options Configuration options
     */
    var setupEventListeners = function(options) {
        var conversationId = null;
        
        // Listen for chat window open events
        document.addEventListener('flowise:chatOpen', function() {
            // Log conversation start
            var promise = Ajax.call([{
                methodname: 'block_flowise_bot_log_conversation',
                args: {
                    session_id: options.sessionId,
                    status: 'active',
                    context_id: options.contextId,
                    course_id: options.courseId
                }
            }])[0];
            
            promise.done(function(response) {
                if (response.success) {
                    conversationId = response.conversation_id;
                }
            });
        });

        // Listen for message submission events
        document.addEventListener('flowise:messageSubmitted', function(e) {
            if (!conversationId) {
                return;
            }
            
            // Log user message
            Ajax.call([{
                methodname: 'block_flowise_bot_log_message',
                args: {
                    conversation_id: conversationId,
                    message: e.detail.message,
                    sender: 'user'
                }
            }]);
        });

        // Listen for message received events
        document.addEventListener('flowise:messageReceived', function(e) {
            if (!conversationId) {
                return;
            }
            
            // Log bot message
            Ajax.call([{
                methodname: 'block_flowise_bot_log_message',
                args: {
                    conversation_id: conversationId,
                    message: e.detail.message,
                    sender: 'bot'
                }
            }]);
        });

        // Listen for chat window close events
        document.addEventListener('flowise:chatClose', function() {
            if (!conversationId) {
                return;
            }
            
            // Update conversation status
            Ajax.call([{
                methodname: 'block_flowise_bot_log_conversation',
                args: {
                    session_id: options.sessionId,
                    status: 'completed',
                    context_id: options.contextId,
                    course_id: options.courseId
                }
            }]);
        });
    };

    // Return the public methods
    return {
        init: init
    };
});
