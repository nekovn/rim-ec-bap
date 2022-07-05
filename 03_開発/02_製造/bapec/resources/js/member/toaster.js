import {Toaster} from '@js/service/notifications';

class ToastMessage {
  constructor() {
    if ($('#toast-message_success')) {
      const msg = $('#toast-message_success').text();
      if (msg) {
        Toaster.showToaster(msg, Toaster.ToastColors.Success);
      }
    }
  }
}
export {ToastMessage};
new ToastMessage('toastMessage');
