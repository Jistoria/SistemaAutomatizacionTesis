import { nextTheme,getCurrentTheme,setTheme,initializeTheme, isThemeReady } from "~/services/themeModel/themeService"
export const useThemeStore = defineStore('theme',()=>{
    const currentTheme = getCurrentTheme();
    const isReady = isThemeReady(); // Obtén el estado de inicialización

    function toggle(){
        nextTheme();
    }
    function updateTheme(theme) {
        setTheme(theme);
    }
    function initTheme() {
        initializeTheme();
    }
    return { currentTheme, toggle,updateTheme,initTheme, isReady };
})