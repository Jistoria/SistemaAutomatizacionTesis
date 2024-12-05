// plugins/laravelEcho.client.ts
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Asigna Pusher al objeto global `window`
window.Pusher = Pusher;
//console.log('Laravel Echo plugin initialized');


const broadcaster = import.meta.env.VITE_BROADCASTER;
const key = import.meta.env.VITE_KEY;
const wsHost = import.meta.env.VITE_WS_HOST;
// const wsPort = import.meta.env.VITE_WS_PORT;
// const wssPort = import.meta.env.VITE_WSS_PORT;

// console.log('Broadcaster:', broadcaster);
// console.log('Key:', key);
// console.log('WS Host:', wsHost);
// console.log('WS Port:', wsPort);
// console.log('WSS Port:', wssPort);


export default defineNuxtPlugin((nuxtApp) => {
  const echo = new Echo({
    broadcaster: broadcaster, // Usa el broadcaster de Reverb
    key: key,// Usa la clave de la variable de entorno
    wsHost:'',
    wssPort:'',
    forceTLS: true,
    enabledTransports: ['wss'],
    disableStats: true,
  });

  // Proporciona Echo a Nuxt para usar en los componentes
  nuxtApp.provide('echo', echo);
});
