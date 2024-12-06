import { ref, watch } from "vue";

const themes = ["UleamTheme" , "Themegrays", "lemonade"];
const isThemeInitialized = ref(false);
const currentTheme = ref(themes[0]);

export function nextTheme() {
  const currentIndex = themes.indexOf(currentTheme.value);
  currentTheme.value = themes[(currentIndex + 1) % themes.length];
  saveThemeToLocalStorage();
}
export function setTheme(theme: string) {
  if (themes.includes(theme)) {
    currentTheme.value = theme;
    saveThemeToLocalStorage();
  }
}
export function getCurrentTheme() {
  return currentTheme;
}
export function initializeTheme() {
  if (typeof window !== "undefined") {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme && themes.includes(savedTheme)) {
      currentTheme.value = savedTheme;
    }
    isThemeInitialized.value = true; // Marca que el tema ya fue inicializado
    document.documentElement.setAttribute("data-theme", currentTheme.value);
  }
}
function saveThemeToLocalStorage() {
  if (typeof window !== "undefined") {
    localStorage.setItem("theme", currentTheme.value);
  }
}
watch(currentTheme, (newTheme) => {
  if (typeof window !== "undefined" && isThemeInitialized.value) {
    document.documentElement.setAttribute("data-theme", newTheme);
  }
});
export function isThemeReady() {
  return isThemeInitialized;
}