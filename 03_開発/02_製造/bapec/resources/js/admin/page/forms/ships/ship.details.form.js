import BaseForm from '@js/app/page/forms/base.form';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import {ModalMessage, Toaster} from '@js/service/notifications';
import Messages from '@js/app/messages';
import StringService from '@js/service/string';

/**
 * 出荷詳細画面クラス
 */
class ShipDetailsForm extends BaseForm {
  /**
   * 画面処理ID
   */
  #functionId;
  /**
   * リクエストURLs
   */
  #requestUrls = {};
  /**
   * 更新時用キー情報
   */
  #updateKey = {
    id: '',
    ol_updated_at: ''
  };
  /**
   * コンストラクタ
   *
   * @param {string} functionId 画面処理ID
   * @param {object} requestUrls リクエストURL
   * @desc 詳細画面で発火するイベントの設定を行う。
   */
  constructor(functionId) {
    super(`#form-${functionId}`);
    this.#functionId = functionId;
    this.#requestUrls = {
      update: '/api/admin/ships/:id',
      back: '/admin/ships/details/back',
      mail: '/api/admin/ships/:id/mail',
      returns: '/api/admin/ships/:id/returns', //返品
      cancel: '/api/admin/ships/:id/cancel'
    };
    this.#updateKey = {
      id: document.querySelector(`#form-${functionId}[data-id]`).dataset.id,
      ol_updated_at: document.querySelector(`#form-${functionId}[data-olddt]`)
        .dataset.olddt
    };
    this.setUpFormObserver();

    /**
     * メール送信ボタン押下
     */
    $(`#${this.#functionId}-btn-mail`).on('click', (e) => {
      //入力チェック
      // super.validate();

      ModalMessage.showConfirm(
        Messages.getMessage('C.shipment.confirmed'),
        this.doSendMail
      );
      // }
    });
    /**
     * 更新ボタン押下
     */
    $(`#${this.#functionId}-btn-update`).on('click', (e) => {
      //入力チェック
      super.validate();

      ModalMessage.showModifyConfirm(
        ModalMessage.MODIFY_MODE.UPD,
        null,
        this.doUpdate
      );
      // }
    });
    /**
     * 戻るボタン押下
     */
    $(`#${this.#functionId}-btn-back`).on('click', (e) => {
      const back = () => {
        window.location.href = this.#requestUrls.back;
      };
      if (this.isFormChanged()) {
        ModalMessage.showConfirm(
          `<p>${fw.i18n.messages['C.changeclose.confirm']}</p>`,
          back
        );
        return;
      }
      back();
    });
    /**
     * 返品ボタン
     */
    $(`#${this.#functionId}-btn-henpin`).on('click', (e) => {
      ModalMessage.showConfirm(
        Messages.getMessage('C.change.confirm', {
          change: 'ステータスを「返品」'
        }),
        this.doStatusChange.bind(
          null,
          this.#requestUrls.returns,
          e.currentTarget
        )
      );
    });
    /**
     * キャンセルボタン
     */
    $(`#${this.#functionId}-btn-cancel`).on('click', (e) => {
      ModalMessage.showConfirm(
        Messages.getMessage('C.change.confirm', {
          change: 'ステータスを「キャンセル」'
        }),
        this.doStatusChange.bind(
          null,
          this.#requestUrls.cancel,
          e.currentTarget
        )
      );
    });
  }
  /**
   * 更新処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   */
  doUpdate = () => {
    const requestParameter = this.createUpdateParameter();

    const url = StringService.bindParameter(
      this.#requestUrls.update,
      this.#updateKey
    );
    const xhrParam = new XhrParam(url, requestParameter);

    const successHandler = (res) => {
      this.#updateKey.ol_updated_at = res.updated_at;
      this.resetFormObserver();
      //伝票番号によりメール送信ボタン制御
      $(`#${this.#functionId}-btn-mail`).attr(
        'disabled',
        !$('input[name="slip_no"]').val()
      );
    };
    XHRCrudMethod.update(xhrParam, successHandler);
  };
  /**
   * 更新時のリクエストパラメーターを作成する。
   * @return {object} リクエストパラメータ(json)
   */
  createUpdateParameter() {
    let paramShip = this.formValueToJson();

    paramShip['ol_updated_at'] = this.#updateKey.ol_updated_at;

    return paramShip;
  }
  /**
   * メール送信を行う。
   * @function
   */
  doSendMail = () => {
    const requestParameter = this.createUpdateParameter();

    const url = StringService.bindParameter(
      this.#requestUrls.mail,
      this.#updateKey
    );
    const xhrParam = new XhrParam(url, requestParameter);

    const successHandler = (res) => {
      this.#updateKey.ol_updated_at = res.updated_at;
      this.resetFormObserver();
      $('#ship-status').text(
        fw.code[fw.define.CodeDefine.SHIP_STATUS].codes[res.status]
      );
      Toaster.showUpdateToaster(fw.i18n.messages['I.mail.complete']);
    };
    XHRCrudMethod.update(xhrParam, successHandler);
  };
  /**
   * ステータス変更処理を行う。
   * @function*/
  doStatusChange = (sendUrl, target) => {
    const requestParameter = this.createUpdateParameter();

    const url = StringService.bindParameter(sendUrl, this.#updateKey);
    const xhrParam = new XhrParam(url, requestParameter);

    const successHandler = (res) => {
      this.#updateKey.ol_updated_at = res.updated_at;
      this.resetFormObserver();
      target.disabled = true;
      if (target.id == `${this.#functionId}-btn-henpin`) {
        document.getElementById(`${this.#functionId}-btn-cancel`).disabled =
          true;
      }
      if (target.id == `${this.#functionId}-btn-cancel`) {
        document.getElementById(`${this.#functionId}-btn-henpin`).disabled =
          true;
      }
      $(`#${this.#functionId}-btn-update`).prop('disabled', true);
      $(`#${this.#functionId}-btn-mail`).prop('disabled', true);
      $('input[name="slip_no"]').prop('disabled', true);
      $('#ship-status').text(
        fw.code[fw.define.CodeDefine.SHIP_STATUS].codes[res.status]
      );
      Toaster.showUpdateToaster(); //正常に更新しました
    };
    XHRCrudMethod.update(xhrParam, successHandler);
  };
}
export default ShipDetailsForm;
