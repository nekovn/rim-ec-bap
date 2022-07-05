/**
 * dialog用js
 * jquery-dialogを使用
 */

class Dialog {
  //dialog基本設定
  static dialogConfig = {
    autoOpen: false, //呼ばれるまで非表示
    modal: true, //モーダル表示
    width: 1300, //ダイアログの横幅(px)
    height: 700, //ダイアログの高さ(px)
    resizable: false,
    position: {
      my: 'center center',
      at: 'center center',
      of: window
    }
  };

  static settingDialog = (dialogSelector, title, dialogCustom) => {
    let dialog = Dialog.dialogConfig;
    dialog.title = title;
    if (dialogCustom) {
      Object.assign(dialog, dialogCustom);
    }
    $(dialogSelector).simpleDialog(dialog);
  };
}
export default Dialog;
