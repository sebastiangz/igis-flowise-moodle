# Flowise Bot para Moodle

Este bloque integra un chatbot impulsado por Flowise en tu plataforma Moodle para ayudar a los estudiantes y visitantes a encontrar información sobre cursos y navegar por la plataforma.

## Requisitos

- Moodle 4.1 o superior (Probado específicamente en Moodle 4.4.6)
- Un servidor Flowise configurado y en funcionamiento
- Un chatflow configurado en Flowise

## Instalación

1. Descarga el código del bloque y colócalo en el directorio `blocks/flowise_bot` de tu instalación de Moodle.
2. Visita la página de notificaciones de administración para completar la instalación.
3. Configura el bloque con la información de tu servidor Flowise:
   - Ve a **Administración del sitio > Plugins > Bloques > Flowise Bot**
   - Introduce el ID del chatflow y la URL del host de API

## Configuración

### Configuración general

- **Chatflow ID**: El identificador de tu chatflow en Flowise
- **API Host**: La URL de tu servidor Flowise (ejemplo: https://tu-servidor-flowise.com)

### Apariencia

Configura el aspecto visual del chatbot:

- Tamaño, color y posición del botón
- Dimensiones de la ventana de chat
- Colores para los mensajes y elementos de la interfaz

### Mensajes

- **Mensaje de bienvenida**: El texto mostrado cuando se abre el chat
- **Mensaje de error**: Texto mostrado cuando ocurre un error de comunicación
- **Prompts iniciales**: Sugerencias predefinidas para ayudar a los usuarios a empezar

### Visualización

Controla dónde y cuándo se muestra el chatbot:

- Páginas específicas donde mostrar el bot
- Auto-apertura del chat
- Restricciones por rol o estado de inicio de sesión

### Configuración avanzada

- CSS y JavaScript personalizado
- Almacenamiento de conversaciones para análisis
- Modo depuración

## Uso

### Para administradores

1. **Agregar el bloque**: Añade el bloque "Flowise Bot" a las páginas deseadas
2. **Ver estadísticas**: Accede a los datos de uso desde la página de administración
3. **Personalizar**: Ajusta el comportamiento y apariencia según las necesidades de tu institución

### Consejos para el desarrollo de tu chatflow

Para orientar mejor a los interesados en cursos de Moodle, considera incluir estos elementos en tu chatflow de Flowise:

1. **Nodos de información sobre cursos**: Conecta con la API de Moodle para obtener información actualizada de cursos
2. **Preguntas frecuentes**: Incluye respuestas a preguntas comunes sobre inscripción, precios, y requisitos
3. **Información contextual**: Usa la información del contexto actual para ofrecer respuestas más relevantes
4. **Flujos de conversación guiados**: Crea flujos específicos para guiar a los usuarios en el proceso de selección de cursos

## Estadísticas y análisis

El bloque proporciona una página de estadísticas que muestra:

- Total de conversaciones
- Total de mensajes intercambiados
- Duración media de las conversaciones
- Preguntas más frecuentes
- Gráficos de uso a lo largo del tiempo

Para acceder, ve a **Administración del sitio > Plugins > Bloques > Flowise Bot > Estadísticas**

## Preguntas frecuentes

### ¿Cómo puedo personalizar aún más el chatbot?

Puedes usar las opciones de CSS y JavaScript personalizado para modificar la apariencia y comportamiento del chatbot.

### ¿Los datos de las conversaciones son privados?

Sí, se almacenan en tu base de datos de Moodle y respetan la privacidad de los usuarios según la configuración de RGPD de Moodle.

### ¿Cómo se conecta con Flowise?

El bloque utiliza la API de Flowise para comunicarse con tu chatflow. Asegúrate de que tu servidor Flowise sea accesible desde tu servidor Moodle.

### ¿Puedo limitar quién puede ver el chatbot?

Sí, puedes configurar el chatbot para que solo se muestre a usuarios con roles específicos o en determinadas páginas.

### ¿Hay algún límite de mensajes?

Los límites dependen de tu implementación de Flowise y los modelos de IA que estés utilizando.

## Solución de problemas

- **El chatbot no aparece**: Verifica la configuración de visualización y asegúrate de que el bloque esté añadido a la página
- **Error de conexión**: Comprueba que la URL del API y el ID del chatflow sean correctos
- **Problemas de rendimiento**: Si el chatbot responde lentamente, revisa la configuración y recursos de tu servidor Flowise

## Créditos

Este bloque fue desarrollado para integrar la potencia de Flowise AI con la plataforma educativa Moodle, facilitando la orientación de estudiantes en la selección de cursos y la navegación por el sistema.
