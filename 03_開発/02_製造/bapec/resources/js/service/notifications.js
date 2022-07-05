/**
 * modalメッセージ制御クラス
 *
 * @classdesc path: @js/service/notification.js
 *
 * <code>
 *  <p>modalmessage.blade.phpが必要</p>
 * </code>
 */
class ModalMessage {
  /**
   * モーダル用のhtml要素のid属性
   * @memberof ModalMessage
   */
  static modalId = '#messagemodal';
  /**
   * @memberof ModalMessage
   * @desc トランザクション実行後のモーダル表示で用いる識別子
   * <div>
   *   <ul>
   *     <li>INS: 登録</li>
   *     <li>UPD: 更新</li>
   *     <li>DEL: 削除</li>
   *   </ul>
   * </div>
   */
  static MODIFY_MODE = {
    INS: 'ins',
    UPD: 'upd',
    DEL: 'del'
  };
  /**
   * @memberof ModalMessage
   * @desc ダイアログタイトル定義
   * <div>
   *   <ul>
   *     <li>INFORMATION: お知らせ</li>
   *     <li>CONFIRM: 実行確認</li>
   *     <li>ERROR: エラー</li>
   *   </ul>
   * </div>
   */
  static DIALOG_TITLE = {
    INFORMATION: 'お知らせ',
    CONFIRM: '実行確認',
    ERROR: 'エラー'
  };
  /**
   * @memberof ModalMessage
   * @desc モーダルダイアログオプション
   * <div>
   *   <ul>
   *     <li>autoOpen: false</li>
   *     <li>modal: true</li>
   *     <li>closeOnEscape: false</li>
   *   </ul>
   * </div>
   */
  static ModalOption = {
    show: false
  };
  /**
   * 初期処理を行う。
   *
   * <p>モーダルのプロパティ設定</p>
   * <p>閉じるボタンクリック時のイベント定義</p>
   *
   * @function
   * @memberof ModalMessage
   */
  static init() {
    if ($(ModalMessage.modalId).length === 0) {
      return;
    }
    $(ModalMessage.modalId).modal(ModalMessage.ModalOption);

    $('#messagemodal-confirm-close-btn').on('click', (e) => {
      $(ModalMessage.modalId).modal('hide');
    });
  }
  /**
   * 更新時確認メッセージダイアログを表示する。
   * @function
   * @memberof ModalMessage
   * @param {string} mode ModalMessage.MODIFY_MODE
   * @param {string} title モーダルタイトル
   * @param {function} okHandler OKボタン押下時のコールバック関数
   */
  static showModifyConfirm(mode, title, okHandler) {
    ModalMessage.modalClear();
    if (!title) {
      title = ModalMessage.DIALOG_TITLE.CONFIRM;
    }
    let theme = 'info';
    let contentHtml;
    if (mode == ModalMessage.MODIFY_MODE.INS) {
      theme = 'info';
      contentHtml = fw.i18n.messages['C.insert.confirm'];
    } else if (mode == ModalMessage.MODIFY_MODE.UPD) {
      theme = 'info';
      contentHtml = fw.i18n.messages['C.update.confirm'];
    } else if (mode == ModalMessage.MODIFY_MODE.DEL) {
      theme = 'danger';
      contentHtml = fw.i18n.messages['C.delete.confirm'];
    }

    $('#messagemodal-title').text(title);
    $('#messagemodal-message').html(contentHtml);
    $('#messagemodal-confirm-ok-btn').addClass('btn-' + theme);

    $(ModalMessage.modalId).modal('show');

    $('#messagemodal-confirm-ok-btn').on('click', function (e) {
      $(ModalMessage.modalId).modal('hide');
      if (okHandler) {
        okHandler(e);
      }
    });
  }
  /**
   * Informationモーダルを表示する。
   * @function
   * @memberof ModalMessage
   * @param {string} message メッセージ
   */
  static showInformation(message) {
    ModalMessage.modalClear();

    $('#messagemodal-title').text(ModalMessage.DIALOG_TITLE.INFORMATION);
    $('#messagemodal-confirm-ok-btn').addClass('btn-info');
    $('#messagemodal-confirm-close-btn').addClass('d-none');
    $('#messagemodal-message').html(message);

    $(ModalMessage.modalId).modal();

    $('#messagemodal-confirm-ok-btn').on('click', function (e) {
      $(ModalMessage.modalId).modal('hide');
    });
  }
  /**
   * Confirmモーダルを表示する。
   * @function
   * @memberof ModalMessage
   * @param {string} message メッセージ
   * @param {function} okHandler OKボタン押下時のコールバック関数
   * @param {function} cancelHandler キャンセルボタン押下時のコールバック関数
   */
  static showConfirm(message, okHandler, cancelHandler) {
    ModalMessage.modalClear();

    $('#messagemodal-title').text(ModalMessage.DIALOG_TITLE.CONFIRM);
    $('#messagemodal-message').html(message);

    $(ModalMessage.modalId).modal();

    $('#messagemodal-confirm-ok-btn').on('click', function (e) {
      $(ModalMessage.modalId).modal('hide');
      if (okHandler) {
        okHandler(e);
      }
    });
    $('#messagemodal-confirm-close-btn').on('click', (e) => {
      $(ModalMessage.modalId).modal('hide');
      if (cancelHandler) {
        cancelHandler(e);
      }
    });
  }
  /**
   * Dangerモーダルを表示する。
   * @function
   * @memberof ModalMessage
   * @param {string} message メッセージ
   */
  static showDanger(message) {
    ModalMessage.modalClear();

    $('#messagemodal-title').text(ModalMessage.DIALOG_TITLE.ERROR);
    $('#messagemodal-confirm-ok-btn').addClass('btn-danger');
    $('#messagemodal-confirm-ok-btn').addClass('d-none');
    $('#messagemodal-confirm-close-btn').text('Close');
    $('#messagemodal-message').html(message);

    $(ModalMessage.modalId).modal();
  }
  /**
   * モーダルのクラスを初期化する。
   * @function
   * @memberof ModalMessage
   */
  static modalClear() {
    $('#messagemodal-title').text('');
    $('#messagemodal-message').html('');
    $('#messagemodal-confirm-ok-btn')
      .removeClass()
      .addClass('btn btn-primary')
      .off('click');
    $('#messagemodal-confirm-close-btn')
      .removeClass()
      .addClass('btn btn-secondary')
      .text('キャンセル');
  }
  /**
   * モーダル表示状態を返す。
   * @function
   * @memberof ModalMessage
   * @param selector 判定対象のセレクタ
   * @return {boolean} true: 表示中, false: 非表示
   */
  static isModalShow(selector) {
    return $(selector).hasClass('show');
  }
}
ModalMessage.init();

/**
 * メッセージ用Toast
 *
 * @classdesc @js/service/notification.js
 */
class Toaster {
  /**
   * トースト色定義
   * <pre>
   *   <div style="color:#1e3a69; background-color:#d7e2f1;">
   *     Primary
   *   </div>
   *   <div style="color:#1b508f; background-color:#d6ebff;">
   *     Info
   *   </div>
   *   <div style="color:#18603a; background-color:#d5f1de;">
   *     Success
   *   </div>
   *   <div style="color:#772b35; background-color:#fadddd;">
   *     Danger
   *   </div>
   *   <div style="color:#815c15; background-color:#feefd0;">
   *     Warning
   *   </div>
   * </pre>
   */
  static ToastColors = {
    Primary: {BGCOLOR: '#4169e1', COLOR: '#ffffff'},
    Info: {BGCOLOR: '#58a9c1', COLOR: '#ffffff'},
    Success: {BGCOLOR: '#71b474', COLOR: '#ffffff'},
    Danger: {BGCOLOR: '#c95d57', COLOR: '#ffffff'},
    Warning: {BGCOLOR: '#f8a841', COLOR: '#ffffff'}
  };
  /**
   * トーストを表示する。
   * @function
   * @memberof Toaster
   * @param message メッセージ
   * @param toastColor Toasterの色
   * @param hideTime トースター表示時間(初期値: 5秒)
   * @param position 表示位置
   */
  static showToaster(message, toastColor, hideTime = 5000, position) {
    if (!position) {
      position = 'top-center';
    }
    if (!toastColor) {
      toastColor = Toaster.ToastColors.Primary;
    }
    const toast = $.toast({
      text: message,
      shoHideTransition: 'fade',
      hideAfter: hideTime,
      position: position,
      stack: 0,
      bgColor: toastColor.BGCOLOR,
      textColor: toastColor.COLOR,
      icon: 'success',
      loader: false
    });
    if (hideTime != false) {
      window.setTimeout(() => {
        toast.reset('all');
      }, hideTime);
    }
  }
  /**
   * 登録完了トーストを表示する。
   * @function
   * @memberof Toaster
   * @param message メッセージ
   */
  static showRegistToaster(message) {
    if (!message) {
      message = fw.i18n.messages['I.insert.complete'];
    }
    Toaster.showToaster(message, Toaster.ToastColors.Success);
  }
  /**
   * 更新完了トーストを表示する。
   * @function
   * @memberof Toaster
   * @param message メッセージ
   */
  static showUpdateToaster(message) {
    if (!message) {
      message = fw.i18n.messages['I.update.complete'];
    }
    Toaster.showToaster(message, Toaster.ToastColors.Success);
  }
  /**
   * コピー完了トーストを表示する。
   * @function
   * @memberof Toaster
   * @param message メッセージ
   */
  static showCopyToaster(message) {
    if (!message) {
      message = fw.i18n.messages['I.copy.complete'];
    }
    Toaster.showToaster(message);
  }
  /**
   * 削除完了トーストを表示する。
   * @function
   * @memberof Toaster
   * @param message メッセージ
   */
  static showDeleteToaster(message) {
    if (!message) {
      message = fw.i18n.messages['I.delete.complete'];
    }
    Toaster.showToaster(message, Toaster.ToastColors.Danger);
  }
  /**
   * エラーメッセージをトーストを表示する。
   * @function
   * @memberof Toaster
   * @param message メッセージ
   * @param hideTime トースター表示時間(初期値：5秒)
   */
  static showAlertToaster(message, hideTime = 5000) {
    const toast = $.toast({
      text: message,
      shoHideTransition: 'fade',
      hideAfter: hideTime,
      position: 'top-center',
      bgColor: Toaster.ToastColors.Danger.BGCOLOR,
      textColor: Toaster.ToastColors.Danger.COLOR,
      stack: 0,
      loader: false
    });
    if (hideTime != false) {
      window.setTimeout(() => {
        toast.reset('all');
      }, hideTime);
    }
  }
  /**
   * 警告メッセージをトーストを表示する。
   * @function
   * @memberof Toaster
   * @param message メッセージ
   * @param hideTime トースター表示時間(初期値：3秒)
   */
  static showWarningToaster(message, hideTime = 3000) {
    $.toast({
      text: message,
      shoHideTransition: 'fade',
      hideAfter: hideTime,
      position: 'top-center',
      textColor: Toaster.ToastColors.Warning.COLOR,
      bgColor: Toaster.ToastColors.Warning.BGCOLOR,
      stack: 1,
      loader: false
    });
  }
  /**
   * Blob型のエラーメッセージをトーストを表示する。
   * @function
   * @memberof Toaster
   * @param blob バイナリーデータ
   */
  static showBlobAlertToaster(blob) {
    //Blobデータ読み込みリーダーオブジェクト
    const reader = new FileReader();

    //Blobデータ読み込み(エラーメッセージ)
    reader.onload = () => {
      var res = JSON.parse(reader.result);
      var errormessage = '';
      for (let i = 0; i < res.data.length; i++) {
        errormessage = errormessage + res.data[i];
      }
      this.showAlertToaster(errormessage);
    };
    reader.readAsText(blob);
  }
  /**
   * トーストの設定をクリアする。
   * @function
   * @memberof Toaster
   */
  static resetAll() {
    $.toast().reset('all');
  }
}
export {ModalMessage, Toaster};
