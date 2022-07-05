import BaseForm from '@js/app/page/forms/base.form';
import {ModalMessage} from '@js/service/notifications';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';

/**
 * ショップ基本情報画面クラス
 */
class ShopinfoForm extends BaseForm {
  /**
   * 画面処理ID
   */
  #functionId;

  /**
   * リクエストURLs
   */
  #requestUrls = {};

  /**
   * コンストラクタ
   * @param functionId
   */
  constructor(functionId) {
    super(`#form-${functionId}`);
    this.#functionId = functionId;
    this.setUpFormObserver();

    this.#requestUrls = {
      find: `/api/admin/shopinfo`,
      store: `/api/admin/shopinfo`
    };

    // 初期表示
    this.doFind();

    /**
     * 登録ボタン押下
     */
    $('#btn-store').on('click', () => {
      // 入力チェック
      super.validate();
      if (this.hasError()) {
        return;
      }

      ModalMessage.showModifyConfirm(
        ModalMessage.MODIFY_MODE.INS,
        '',
        this.doRegister
      );
    });

    // 画面変更イベント
    super.addEventBeforeUnload();
  }

  /**
   * 取得処理を行う。
   */
  doFind() {
    const url = this.#requestUrls.find;
    const xhrParam = new XhrParam(url);
    const successHandler = (res) => {
      this.bindValue(res.data);
    };

    XHRCrudMethod.get(xhrParam, successHandler);
  }

  /**
   * 登録処理を行う。
   */
  doRegister = () => {
    const url = this.#requestUrls.store;
    const xhrParam = new XhrParam(url, this.createParameter());
    const successHandler = (res) => {
      this.resetFormObserver();
    };

    XHRCrudMethod.store(xhrParam, successHandler);
  };

  /**
   * 登録時のリクエストパラメーターを作成する。
   */
  createParameter() {
    const param = this.formValueToJson();

    $(`#form-${this.#functionId} [data-html-only]`).each((index, element) => {
      delete param[element.id];
    });

    return param;
  }
}

export default ShopinfoForm;
