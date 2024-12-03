import msalInstance from "~/utils/msalConfig";
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
  const login = async () => {
    try {
      // Inicializa MSAL si no está inicializado
      if (!msalInstance.getAllAccounts().length) {
        await msalInstance.initialize();
      }

      // Configuración de la solicitud de inicio de sesión
      const loginRequest = {
        scopes: ["openid", "profile", "email"],
        prompt: "select_account",
      };

      // Iniciar sesión con una ventana emergente
      const loginResponse = await msalInstance.loginPopup(loginRequest);
      console.log("Login Response:", loginResponse);

      return loginResponse.account;
    } catch (error) {
      console.error("Error during login:", error);
      return null;
    }
  };

  const getUserProfile = async () => {
    try {
      const account = msalInstance.getAllAccounts()[0];
      if (!account) {
        console.error("No account found. User not logged in.");
        return null;
      }

      return {
        name: account.name,
        email: account.username,
      };
    } catch (error) {
      console.error("Error fetching user profile:", error);
      return null;
    }
  };

  return { getAccessToken, login, getUserProfile };
};
