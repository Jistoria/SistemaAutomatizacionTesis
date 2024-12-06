type GoogleFontsDisplay = 'auto' | 'block' | 'swap' | 'fallback' | 'optional';

export default {
    families: {
      'Bree+Serif': true, // Incluye la fuente Bree Serif
      Roboto: [100, 300, 400, 700], // Puedes añadir más fuentes si lo necesitas
    },
    display: 'swap' as GoogleFontsDisplay,
};