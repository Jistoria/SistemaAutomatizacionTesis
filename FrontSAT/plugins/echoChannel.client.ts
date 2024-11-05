// plugins/echoChannel.client.ts
import { useNuxtApp } from '#imports';
import { auth } from '~/stores/auth/auth';

export default defineNuxtPlugin(() => {
  const { $echo } = useNuxtApp();
  const authStore = auth();
  authStore.online = false;

  // Crear una promesa que resolverá cuando se conecte
  const echoReady = new Promise((resolve) => {
    if (!$echo) {
      console.error('$echo no está definido');
      return;
    }

    // Acceder al objeto `pusher` para eventos de conexión
    const pusher = $echo.connector.pusher;
    let previousState = 'disconnected';
    let isReconnecting = false;
    let isInitialConnection = true; // Nueva bandera para detectar la primera conexión

    // Evento de cambio de estado de conexión
    pusher.connection.bind('state_change', (states: any) => {
      console.log('Cambio de estado:', states.current);

      if (states.current === 'connected') {
        console.log('Conectado a Pusher');

        if (!isInitialConnection && (previousState === 'disconnected' || previousState === 'unavailable')) {
          isReconnecting = true;
          console.log('Reconexión detectada');
          // Aquí puedes manejar lógica de reconexión específica
        } else {
          isReconnecting = false;
        }

        authStore.online = true;
        resolve(true); // Resolver la promesa cuando esté conectado
        isInitialConnection = false; // Marcar que la conexión inicial ya ocurrió
      } else if (states.current === 'disconnected' || states.current === 'unavailable') {
        console.log('Desconectado de Pusher');
        authStore.online = false;
        isReconnecting = false;
      } else if (states.current === 'connecting') {
        console.log('Intentando conectar...');
        
        // Temporizador para detectar reconexión en estado "connecting"
        setTimeout(() => {
          if (states.current === 'connecting' && (previousState === 'disconnected' || previousState === 'unavailable')) {
            isReconnecting = true;
            console.log('Reconectando...');
          }
        }, 3000); // Ajusta el tiempo según lo necesario
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

  // Exponer `echoReady` para que esté disponible
  return {
    provide: {
      echoReady,
    },
  };
});
