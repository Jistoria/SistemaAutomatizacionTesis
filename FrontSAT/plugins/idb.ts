/**
 * Importa los módulos requeridos de la librería 'idb' para la gestión de IndexedDB.
 */
import { openDB } from 'idb';
import type { IDBPDatabase, DBSchema } from 'idb';

/**
 * Define la interfaz del esquema de la base de datos para la aplicación.
 * La tienda 'auth' contiene registros con una 'key', estado de 'session', información del 'user' y un 'token' de autenticación.
 */
interface MyDB extends DBSchema {
  auth: {
    key: string;
    value: {
      key: string;
      session: boolean;
      user: Object;
      token: string;
    };
  };
  student_data: {
    key: string;
    value: any; // Cada segmento de estado será guardado con su clave única
  };
  admin_data: {
    key: string;
    value: any; // Cada segmento de estado será guardado con su clave única
  };
}

/**
 * Declara una variable para mantener la referencia a la base de datos.
 */
let db: IDBPDatabase<MyDB> | null = null;

/**
 * Abre o crea la base de datos de manera asincrónica.
 * Si la base de datos no existe, se creará con la versión y el esquema especificados.
 * La tienda 'auth' se crea si no existe.
 *
 * @returns {Promise<IDBPDatabase<MyDB>>} - Una promesa que resuelve la instancia de la base de datos.
 */

export const getDatabase = async (): Promise<IDBPDatabase<MyDB>> => {
  if (!db) {
    db = await openDB('SAT', 3, {  // Incrementar la versión a 3
      upgrade(db, oldVersion) {
        // Crear la tienda 'auth' con clave en línea 'key'
        if (!db.objectStoreNames.contains('auth')) {
          db.createObjectStore('auth', { keyPath: 'key' });
        }

        // Crear la tienda 'student_data' sin clave en línea pero con autoincrement
        if (!db.objectStoreNames.contains('student_data')) {
          db.createObjectStore('student_data', { autoIncrement: true });
        }

        // Crear la tienda 'admin' con clave en línea 'adminId'
        if (!db.objectStoreNames.contains('admin_data')) {
          db.createObjectStore('admin_data', { autoIncrement: true });
        }
      },
    });
  }
  return db;
};


/**
 * Define un plugin de Nuxt para proporcionar la instancia de IndexedDB a la aplicación.
 * Este plugin solo se ejecuta en el lado del cliente, ya que IndexedDB no está disponible en el lado del servidor.
 *
 * @param {any} nuxtApp - La instancia de la aplicación Nuxt.
 */
export default defineNuxtPlugin(async (nuxtApp) => {
  if (process.server) {
    return;
  }

  db = await getDatabase();
  nuxtApp.provide('idb', db);
});

