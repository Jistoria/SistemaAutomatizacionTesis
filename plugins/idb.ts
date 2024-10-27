// plugins/idb.js
import { openDB } from 'idb';
/**
 * Plugin de Nuxt que configura una base de datos indexada (IndexedDB) utilizando `openDB`.
 * 
 * @returns Un objeto que proporciona la promesa de la base de datos (`dbPromise`).
 * 
 * @example
 * ```typescript
 * export default defineNuxtPlugin(() => {
 *   const dbPromise = openDB('my-database', 1, {
 *     upgrade(db) {
 *       // Crear un almacen de objetos si no existe
 *       if (!db.objectStoreNames.contains('my-store')) {
 *         db.createObjectStore('my-store', { keyPath: 'id' });
 *       }
 *     },
 *   });
 * 
 *   return {
 *     provide: {
 *       idb: dbPromise,
 *     },
 *   };
 * });
 * ```
 */
export default defineNuxtPlugin(() => {
  const dbPromise = openDB('SAT', 1, {
    upgrade(db) {
      // Crear un almacen de objetos si no existe
      if (!db.objectStoreNames.contains('auth')) {
        db.createObjectStore('auth', { keyPath: 'id' });
      }
    },
  });

  return {
    provide: {
      idb: dbPromise,
    },
  };
});
