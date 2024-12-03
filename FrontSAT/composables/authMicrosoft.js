export const useMicrosoftAuth = () => {
  const getAccessToken = async () => {
    try {
      // Llamada al backend para obtener el token
      const url = import.meta.env.VITE_API_URL;
      const response = await $fetch(`${url}/api/microsoft/token`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
        },
      });

      if (response && response.access_token) {
        console.log('Access Token:', response.access_token);
        return response.access_token; // Devuelve el token al cliente
      } else {
        console.error('No se pudo obtener el token:', response);
        return null;
      }
    } catch (error) {
      console.error('Error obteniendo el token desde el backend:', error);
      return null;
    }
  };

  return { getAccessToken };
};
