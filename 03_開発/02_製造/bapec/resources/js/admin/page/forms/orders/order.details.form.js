import BaseForm from '@js/app/page/forms/base.form';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import {ModalMessage} from '@js/service/notifications';
import StringService from '@js/service/string';

/**
 * 受注詳細画面クラス
 */
class OrderDetailsForm extends BaseForm {
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
      update: '/api/admin/orders/:id',
      back: '/admin/orders/details/back'
    };
    this.#updateKey = {
      id: document.querySelector(`#form-${functionId}[data-id]`).dataset.id,
      ol_updated_at: document.querySelector(`#form-${functionId}[data-olddt]`)
        .dataset.olddt
    };
    this.setUpFormObserver();

    /**
     * 注文ステータス変更
     */
    $(`#form-${functionId} select[name="status"]`).on('change', (e) => {
      const status = document.getElementsByName('status')[0].value;
      if (status == fw.define.StatusDefine.CANCEL) {
        if (
          fw.code[fw.define.CodeDefine.PAYMENT_STATUS].codes[1] ==
            $('#payment-status').data('payment-status-name') &&
          $('#payment-status').data('payment-method-name') !== '代金引換' &&
          $('#payment-status').data('payment-method-name') !== '請求無し'
        ) {
          // 決済方法が代金引換以外 かつ 決済ステータスが決済済みの場合のみ表示
          document.getElementById('status-warning').classList.remove('d-none');
        }
      } else {
        document.getElementById('status-warning').classList.add('d-none');
      }
    });
    /**
     * 更新ボタン押下
     */
    $(`#${this.#functionId}-btn-update`).on('click', (e) => {
      //画面で変更が無い時は変更しない
      if (this.isFormChanged() == false) {
        return;
      }
      //入力チェック
      super.validate();

      //キャンセルかどうか
      // const status = document.getElementsByName('status').values;
      // if (status == fw.defain.StatusDefine.CANCEL) {
      //   ModalMessage.showConfirm(, this.doUpdate);
      // } else {
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
      //受付時以外は非活性にする
      const status = document.getElementsByName('status').values;
      if (status != fw.define.StatusDefine.UKETSUKE) {
        this.changeDisabled(`${this.formSelector}`, true);
        $(`#${this.#functionId}-btn-update`).hide();
      }
    };
    XHRCrudMethod.update(xhrParam, successHandler);
  };
  /**
   * 更新時のリクエストパラメーターを作成する。
   * @return {object} リクエストパラメータ(json)
   */
  createUpdateParameter() {
    //受注
    let paramOrder = this.formValueToJson();

    //その他テーブル用
    let paramDelivery = [];

    $(`#form-${this.#functionId} [data-deliveryid]`).each((index, element) => {
      delete paramOrder[element.name];
      let key = element.dataset.deliveryid;
      let deliData = null;
      for (let idx = 0; idx < paramDelivery.length; idx++) {
        if (paramDelivery[idx].id == key) {
          deliData = paramDelivery[idx];
          break;
        }
      }
      if (deliData == null) {
        deliData = {id: key};
        paramDelivery.push(deliData);
      }
      deliData[element.name] = element.value;
    });

    let param = {
      order: paramOrder,
      orderDelivery: paramDelivery,
      ol_updated_at: this.#updateKey.ol_updated_at
    };

    return param;
  }
}
export default OrderDetailsForm;
