import Swal  from "sweetalert2";
/**
 * Plugin de Nuxt que proporciona la funcionalidad de SweetAlert a la aplicación.
 * 
 * @param nuxtApp - La instancia de la aplicación Nuxt.
 * 
 * @example
 * // Uso en un componente de Vue
 * this.$swal('¡Hola Mundo!');
 */
export default defineNuxtPlugin((nuxtApp)=>{
    nuxtApp.provide('swal', Swal)
})