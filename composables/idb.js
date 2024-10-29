

/**
 * Función para acceder a la tabla (Object Store) especificada y obtener los elementos.
 * 
 * @function consult
 * @async
 * @param {string} tableName - El nombre de la tabla (Object Store) a consultar.
 * @returns {Promise<Object|Array|{error: string}>} - Devuelve el primer elemento de la tabla como objeto, 
 * un array vacío si no hay elementos, o un objeto de error si ocurre algún problema.
 * 
 * @example
 * const { consult } = useIdb();
 * const result = await consult('miTabla');
 * if (result.error) {
 *   console.error(result.error);
 * } else {
 *   console.log(result);
 * }
 */
// composables/useIndexedDB.js
import { getDatabase } from '~/plugins/idb';

export function useIdb() {
  // Función para acceder a la tabla (Object Store) especificada y obtener los elementos
  const consult = async (tableName) => {
    try {
      // Obtener la base de datos usando el método getDatabase
      const db = await getDatabase();

      if (!db) {
        console.error('No se pudo acceder a la base de datos.');
        return { error: 'No se pudo acceder a la base de datos.' };
      }

      // Verificar si el Object Store existe
      if (!db.objectStoreNames.contains(tableName)) {
        console.error(`La tabla '${tableName}' no existe.`);
        return { error: `La tabla '${tableName}' no existe.` };
      }

      // Iniciar una transacción de lectura
      const tx = db.transaction(tableName, 'readonly');
      const store = tx.objectStore(tableName);
      const items = await store.getAll();

      if (!items.length) {
        console.log(`No hay elementos en la tabla '${tableName}'.`);
        return items;
      }

      // Convertir el primer elemento del array a un objeto directamente
      const item = items[0];
      return item; // Devuelve el primer elemento como objeto
    } catch (error) {
      console.error('Error al acceder a IndexedDB:', error);
      return { error: 'Error al acceder a IndexedDB.' };
    }
  };

  return {
    consult
  };
}
