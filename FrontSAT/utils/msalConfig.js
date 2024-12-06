import { PublicClientApplication } from "@azure/msal-browser";

// Configuración de MSAL
const msalConfig = {
  auth: {
    clientId: "bca96570-6248-4c2a-aa16-15c985685375", // ID de tu aplicación registrada en Azure
    authority: "https://login.microsoftonline.com/31a17900-7589-4cfc-b11a-f4e83c27b8ed", // URL del tenant de Azure AD
    redirectUri: "http://localhost:3000/auth/callback", // Redirección después del login
  },
  cache: {
    cacheLocation: "localStorage", // Cambiar a "sessionStorage" si prefieres no almacenar en local
    storeAuthStateInCookie: false, // Cambiar a true si tienes problemas con cookies
  },
};

const msalInstance = new PublicClientApplication(msalConfig);

export default msalInstance;
