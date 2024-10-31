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

    // Evento de cambio de estado de conexión
    pusher.connection.bind('state_change', (states: any) => {
      console.log('Cambio de estado:', states.current);

      if (states.current === 'connected') {
        console.log('Conectado a Pusher');
        authStore.online = true;
        resolve(true); // Resolver la promesa cuando esté conectado
      } else if (states.current === 'disconnected' || states.current === 'unavailable') {
        console.log('Desconectado de Pusher');
        authStore.online = false;
      }
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
