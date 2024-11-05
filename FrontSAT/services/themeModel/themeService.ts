import { ref } from "vue";
const themes=[
    "light",
    "cupcake",
    "lemonade",
];
export const currentTheme = ref(themes[0]);

export function nextTheme() {
    const currentIndex = themes.indexOf(currentTheme.value);
    currentTheme.value = themes[(currentIndex + 1) % themes.length];
}
export function setTheme(theme: string) {
    if (themes.includes(theme)) {
      currentTheme.value = theme;
      console.log(currentTheme.value)
    }
}
  
  // Función para obtener el tema actual
export function getCurrentTheme() {
    return currentTheme;
}