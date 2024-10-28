import Swal from 'sweetalert2';

export function sweetAlert() {
  const showAlert = (icon, position = 'normal', { title, text = '', confirmType = 'normal' }) => {
    const validIcons = ['success', 'error', 'warning', 'info', 'question'];
    if (!validIcons.includes(icon)) {
      console.error('Icon type is not valid');
      return;
    }

    const swalOptions = {
      icon,
      title,
      text,
    };

    // Handle position
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

    // Handle confirmType
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
        swalOptions.timerProgressBar= true;
        swalOptions.showConfirmButton = false;
        break;
      case 'normal':
      default:
        swalOptions.showConfirmButton = true;
        break;
    }

    return Swal.fire(swalOptions);
  };

  return {
    showAlert,
  };
}

// Ejemplo de uso del composable:
// import { useSweetAlert } from '@/composables/useSweetAlert';
// const { showAlert } = useSweetAlert();
// showAlert('success', 'top-right', { title: '¡Operación Exitosa!', text: 'Los datos fueron guardados correctamente', confirmType: 'normal' });
