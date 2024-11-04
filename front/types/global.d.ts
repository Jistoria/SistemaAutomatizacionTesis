// globals.d.ts o types/globals.d.ts

// Declara que `Pusher` es una propiedad en `window`
export {};

declare global {
  interface Window {
    Pusher: any;
  }
}
