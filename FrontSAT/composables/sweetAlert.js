/**
 * Función para crear alertas personalizadas con SweetAlert.
 * 
 * @module sweetAlert
 * 
 * Este módulo proporciona una función de utilidad `sweetAlert` que permite crear alertas personalizadas usando SweetAlert2.
 * Soporta varios tipos de alertas (`success`, `error`, `warning`, `info`, `question`) y ofrece opciones para ajustar la posición y el comportamiento de la alerta.
 */
import Swal from 'sweetalert2';


/**
 * Crea una alerta de SweetAlert con opciones personalizables.
 * 
 * @function sweetAlert
 * 
 * @returns {Object} Contiene la función `showAlert` que se puede usar para crear alertas.
 */
export function sweetAlert() {
  /**
   * Muestra una alerta de SweetAlert.
   * 
   * @function showAlert
   * 
   * @param {string} icon - El tipo de alerta a mostrar (success, error, warning, info, question).
   * @param {string} [position='normal'] - La posición de la alerta. Puede ser 'normal' (centro), 'right' (parte superior derecha) o 'left' (parte superior izquierda).
   * @param {Object} options - Opciones para personalizar la alerta.
   * @param {string} options.title - El título de la alerta.
   * @param {string} [options.text=''] - La descripción de la alerta.
   * @param {string} [options.confirmType='normal'] - El tipo de confirmación de la alerta. Puede ser 'normal', 'confirm' (sí/no) o 'timer' (auto-descartar).
   * 
   * @returns {Promise|void} - Retorna una promesa que se resuelve a `true` o `false` cuando se usa el tipo 'confirm', o void para otros tipos.
   */
  const showAlert = (icon, position = 'normal', { title, text = '', confirmType = 'normal' }) => {
    const validIcons = ['success', 'error', 'warning', 'info', 'question'];
    if (!validIcons.includes(icon)) {
      console.error('El tipo de icono no es válido');
      return;
    }

    const swalOptions = {
      icon,
      title,
      text,
    };

    // Manejar la posición
    switch (position) {
      case 'right':
        swalOptions.position = 'top-end';
        swalOptions.toast = true;
        break;
      case 'left':
        swalOptions.position = 'top-start';
        swalOptions.toast = true;
        break;
      case 'normal':
      default:
        swalOptions.position = 'center';
        break;
    }

    // Manejar el tipo de confirmación
    switch (confirmType) {
      case 'confirm':
        swalOptions.showCancelButton = true;
        swalOptions.confirmButtonText = 'Si';
        swalOptions.cancelButtonText = 'No';
        return Swal.fire(swalOptions).then((result) => {
          return result.isConfirmed ? true : false;
        });
      case 'timer':
        swalOptions.timer = 2500;
        swalOptions.timerProgressBar = true;
        swalOptions.showConfirmButton = false;
        break;
      case 'normal':
      default:
        swalOptions.showConfirmButton = true;
        break;
    }

    return Swal.fire(swalOptions);
  };
  const showLoadingToast = (title, text = '') => {
    Swal.fire({
      title,
      text,
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      showCancelButton: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
  };

  const closeLoadingToast = () => {
    Swal.close();
  };
  

  return {
    showAlert,
    showLoadingToast,
    closeLoadingToast,
  };
}

// Ejemplo de uso del composable:
// import { useSweetAlert } from '@/composables/useSweetAlert';
// const { showAlert } = useSweetAlert();
// showAlert('success', 'top-right', { title: '¡Operación Exitosa!', text: 'Los datos fueron guardados correctamente', confirmType: 'normal' });
