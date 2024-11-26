// sweetAlert.d.ts

import { SweetAlertIcon } from 'sweetalert2';

interface AlertOptions {
  title: string;
  text?: string;
  confirmType?: 'normal' | 'confirm' | 'timer';
}

interface SweetAlertComposable {
  showAlert: (
    icon: SweetAlertIcon,
    position?: 'normal' | 'right' | 'left',
    options?: AlertOptions
  ) => Promise<boolean | void>;
  showLoadingToast: (title: string, text?: string) => void;
  closeLoadingToast: () => void;
}

declare module '#imports' {
  export function sweetAlert(): SweetAlertComposable;
}
