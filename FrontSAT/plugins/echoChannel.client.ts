// plugins/echoChannel.client.ts
import { useNuxtApp } from '#imports';
import { auth } from '~/stores/auth/auth';

export default defineNuxtPlugin(() => {
  const { $echo } = useNuxtApp();
  const authStore = auth();
  authStore.online = false;

  // Crear una promesa que resolverá cuando se conecte o timeout
  const echoReady = new Promise((resolve) => {
    if (!$echo) {
      console.error('$echo no está definido');
      resolve(false); // Resolver como false para que el flujo principal continúe
      return;
    }

    // Acceder al objeto `pusher` para eventos de conexión
    const pusher = $echo.connector.pusher;
    let previousState = 'disconnected';
    let isReconnecting = false;
    let isInitialConnection = true;

    // Timeout para evitar bloqueo
    const timeout = setTimeout(() => {
      console.warn('Timeout: No se pudo conectar al WebSocket inicialmente.');
      resolve(false); // Resolver sin conexión inicial
    }, 5000); // Configura el tiempo de espera según sea necesario

    // Evento de cambio de estado de conexión
    pusher.connection.bind('state_change', (states: any) => {
      console.log('Cambio de estado:', states.current);

      if (states.current === 'connected') {
        console.log('Conectado a Pusher');
        clearTimeout(timeout); // Cancelar timeout en caso de éxito

        if (!isInitialConnection && (previousState === 'disconnected' || previousState === 'unavailable')) {
          isReconnecting = true;
          console.log('Reconexión detectada');
          // Lógica específica para reconexión
        } else {
          isReconnecting = false;
        }

        authStore.online = true;
        resolve(true); // Resolver la promesa cuando esté conectado
        isInitialConnection = false;
      } else if (states.current === 'disconnected' || states.current === 'unavailable') {
        console.log('Desconectado de Pusher');
        authStore.online = false;
        isReconnecting = false;
      } else if (states.current === 'connecting') {
        console.log('Intentando conectar...');

        // Temporizador para detectar reconexión
        setTimeout(() => {
          if (states.current === 'connecting' && (previousState === 'disconnected' || previousState === 'unavailable')) {
            isReconnecting = true;
            console.log('Reconectando...');
          }
        }, 3000);
      }

      // Actualizar el estado previo
      previousState = states.current;
    });

    // Suscribirse al canal "testChannel" y escuchar eventos personalizados
    const channel = $echo.channel('testChannel');

    channel.listen('.AuthEvent', (data: any) => {
      console.log('Evento recibido:', data);
    });
  });

  // Intentar reconectar de forma automática en segundo plano
  const reconnectWithDelay = (delay = 5000) => {
    const pusher = $echo.connector.pusher;
    pusher.connection.bind('disconnected', () => {
      console.log(`[WebSocket] Reconexión programada en ${delay / 1000} segundos.`);
      setTimeout(() => {
        console.log('[WebSocket] Intentando reconectar...');
        pusher.connect();
      }, delay);
    });
  };

  // Activar reconexión en segundo plano
  reconnectWithDelay();

  // Exponer `echoReady` para que esté disponible
  return {
    provide: {
      echoReady,
    },
  };
});

