/**
 * JavaScript for IGIS Flowise Bot integration
 *
 * @package    local_igisflowise
 * @copyright  2025 InfraestructuraGIS (https://www.infraestructuragis.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/log'], function($, log) {
    'use strict';
    
    /**
     * Inicializa el Bot de Flowise
     *
     * @param {Object} config Configuración del bot
     * @return {Promise} Promise que se resuelve cuando el bot se inicializa
     */
    var init = function(config) {
        log.debug('IGIS Flowise Bot: Inicializando con configuración', config);
        
        // Genera un ID de sesión único si no existe
        var sessionId = localStorage.getItem('igis_bot_session_id');
        if (!sessionId) {
            sessionId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            localStorage.setItem('igis_bot_session_id', sessionId);
        }
        
        // Cargar la biblioteca Flowise desde CDN
        return new Promise(function(resolve, reject) {
            // Usamos require.ensure que es la forma de cargar módulos dinámicamente con RequireJS
            require(['https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js'], function(Chatbot) {
                try {
                    // Inicializa el chatbot con la configuración proporcionada
                    Chatbot.init(config);
                    log.debug('IGIS Flowise Bot: Chatbot inicializado correctamente');
                    
                    // Configurar los manejadores de eventos si está habilitado el registro de conversaciones
                    if (config.saveConversations) {
                        setupEventHandlers(config, sessionId);
                    }
                    
                    resolve({
                        sessionId: sessionId,
                        config: config
                    });
                } catch (error) {
                    log.error('IGIS Flowise Bot: Error al inicializar el chatbot', error);
                    reject(error);
                }
            }, function(error) {
                log.error('IGIS Flowise Bot: Error al cargar la biblioteca Flowise', error);
                reject(error);
            });
        });
    };
    
    /**
     * Configura los manejadores de eventos para el chatbot
     * 
     * @param {Object} config Configuración del bot
     * @param {String} sessionId ID de sesión
     */
    var setupEventHandlers = function(config, sessionId) {
        var conversationId = null;
        
        // Registrar inicio de conversación
        $(document).on('flowise:chatOpen', function() {
            $.ajax({
                url: config.wwwroot + '/local/igisflowise/ajax.php',
                method: 'POST',
                data: {
                    action: 'log_conversation',
                    sesskey: config.sesskey,
                    session_id: sessionId,
                    status: 'active'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        conversationId = response.data.conversation_id;
                        log.debug('IGIS Flowise Bot: Conversación iniciada', conversationId);
                    }
                }
            });
        });
        
        // Registrar mensajes del usuario
        $(document).on('flowise:messageSubmitted', function(e) {
            if (!conversationId) return;
            
            $.ajax({
                url: config.wwwroot + '/local/igisflowise/ajax.php',
                method: 'POST',
                data: {
                    action: 'log_message',
                    sesskey: config.sesskey,
                    conversation_id: conversationId,
                    message: e.detail.message,
                    type: 'user'
                },
                dataType: 'json'
            });
        });
        
        // Registrar respuestas del bot
        $(document).on('flowise:messageReceived', function(e) {
            if (!conversationId) return;
            
            $.ajax({
                url: config.wwwroot + '/local/igisflowise/ajax.php',
                method: 'POST',
                data: {
                    action: 'log_message',
                    sesskey: config.sesskey,
                    conversation_id: conversationId,
                    message: e.detail.message,
                    type: 'bot'
                },
                dataType: 'json'
            });
        });
        
        // Actualizar estado de la conversación al cerrar
        $(document).on('flowise:chatClose', function() {
            if (!conversationId) return;
            
            $.ajax({
                url: config.wwwroot + '/local/igisflowise/ajax.php',
                method: 'POST',
                data: {
                    action: 'log_conversation',
                    sesskey: config.sesskey,
                    session_id: sessionId,
                    status: 'completed'
                },
                dataType: 'json'
            });
        });
    };
    
    return {
        init: init
    };
});
