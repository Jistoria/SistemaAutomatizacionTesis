import { getDatabase } from '~/plugins/idb';

export function useIdb() {
  /**
   * Consultar todos los elementos en una tabla (Object Store).
   */
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
  const deleteField = async (tableName, key) => {
    try {
      const db = await getDatabase();

      if (!db) {
        console.error('No se pudo acceder a la base de datos.');
        return { error: 'No se pudo acceder a la base de datos.' };
      }

      if (!db.objectStoreNames.contains(tableName)) {
        console.error(`La tabla '${tableName}' no existe.`);
        return { error: `La tabla '${tableName}' no existe.` };
      }

      // Inicia una transacción en modo "readwrite" para modificar datos
      const tx = db.transaction(tableName, 'readwrite');
      const store = tx.objectStore(tableName);

      // Elimina el elemento con la clave especificada
      await store.delete(key);

      await tx.done;

      console.log(`Campo con clave '${key}' eliminado de la tabla '${tableName}'.`);
      return { success: true };
    } catch (error) {
      console.error('Error al eliminar campo en IndexedDB:', error);
      return { error: 'Error al eliminar campo en IndexedDB.' };
    }
  };
  const consults = async (tableName, key) => {
    try {
      const db = await getDatabase();
  
      if (!db) {
        console.error('No se pudo acceder a la base de datos.');
        return { error: 'No se pudo acceder a la base de datos.' };
      }
  
      if (!db.objectStoreNames.contains(tableName)) {
        console.error(`La tabla '${tableName}' no existe.`);
        return { error: `La tabla '${tableName}' no existe.` };
      }
  
      const tx = db.transaction(tableName, 'readonly');
      const store = tx.objectStore(tableName);
  
      if (key) {
        const item = await store.get(key);

        return item || null;
      } else {
        const items = await store.getAll();

        return items;
      }
    } catch (error) {
      console.error('Error al acceder a IndexedDB:', error);
      return { error: 'Error al acceder a IndexedDB.' };
    }
  };
  
  /**
   * Guardar un elemento en una tabla (Object Store) específica.
   */
  const setData = async (tableName, key, value) => {
    try {
      const db = await getDatabase();
      if (!db) {
        throw new Error('No se pudo acceder a la base de datos.');
      }
  
      const tx = db.transaction(tableName, 'readwrite');
      const store = tx.objectStore(tableName);
  
      if (key) {
        // Proporciona la clave explícitamente
        await store.put(value, key);
      } else {
        // Si no hay clave, intenta guardar sin ella (requiere autoincrement)
        await store.put(value);
      }
  
      await tx.done;
      // console.log(`Datos guardados correctamente en la tabla '${tableName}'.`);
    } catch (error) {
      console.error('Error al guardar datos en IndexedDB:', error);
    }
  };

  /**
   * Limpiar todos los elementos de una tabla (Object Store) específica.
   */
  const clearData = async (tableName) => {
    try {
      const db = await getDatabase();
      if (!db) {
        throw new Error('No se pudo acceder a la base de datos.');
      }

      console.log(`Limpiando todos los datos de la tabla '${tableName}'...`);

      const tx = db.transaction(tableName, 'readwrite');
      const store = tx.objectStore(tableName);
      await store.clear();
      await tx.done;

      console.log(`Tabla '${tableName}' limpiada correctamente.`);
    } catch (error) {
      console.error('Error al limpiar datos en IndexedDB:', error);
    }
  };

  return {
    consult,
    consults,
    setData,
    clearData,
    deleteField,
  };
}

