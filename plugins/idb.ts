import { openDB } from 'idb';
import type { IDBPDatabase, DBSchema } from 'idb';

interface MyDB extends DBSchema {
  auth: {
    key: string;
    value: {
      session: string;
      user: string;
      token: string;
    };
  };
}

let db: IDBPDatabase<MyDB> | null = null;

export const getDatabase = async (): Promise<IDBPDatabase<MyDB>> => {
  if (!db) {
    db = await openDB<MyDB>('SAT', 1, {
      upgrade(db) {
        if (!db.objectStoreNames.contains('auth')) {
          db.createObjectStore('auth', { keyPath: 'key' });
        }
      },
    });
  }
  return db;
};

export default defineNuxtPlugin(async (nuxtApp) => {
  if (process.server) {
    return;
  }

  db = await getDatabase();
  nuxtApp.provide('idb', db);
});

