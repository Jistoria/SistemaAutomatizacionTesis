import { nextTheme,getCurrentTheme,setTheme } from "~/services/themeModel/themeService"
export const useThemeStore = defineStore('theme',()=>{
    const currentTheme = getCurrentTheme();

    function toggle(){
        nextTheme();
    }
    function updateTheme(theme) {
        setTheme(theme);
    }

    return { currentTheme, toggle,updateTheme };
})