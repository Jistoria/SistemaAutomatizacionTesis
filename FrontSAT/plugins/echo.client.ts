// plugins/laravelEcho.client.ts
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Asigna Pusher al objeto global `window`
window.Pusher = Pusher;

export default defineNuxtPlugin((nuxtApp) => {
  const echo = new Echo({
    broadcaster: 'reverb', // Usa el broadcaster de Reverb
    key: '4c9e0v0wg5rd8kmj62sb',            // Usa la clave de la variable de entorno
    wsHost: 'localhost',
    wsPort: '8080',
    wssPort: '8080',
    forceTLS: false,
    enabledTransports: ['ws'],
    disableStats: true,
  });

  // Proporciona Echo a Nuxt para usar en los componentes
  nuxtApp.provide('echo', echo);
});
