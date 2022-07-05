import BaseForm from '@js/app/page/forms/base.form';
import AnimateService from '@js/service/animate';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import {ModalMessage} from '@js/service/notifications';
import StringService from '@js/service/string';

/**
 * simple.crudテンプレートの詳細Form。C,U,Dの機能を提供する。<br>
 * 削除機能は、詳細画面で削除を行いたい場合用。
 * @extends {BaseForm}
 * @classdesc path: @js/base/forms.simple.crud.detail.form.js
 */
class SimpleCrudDetailForm extends BaseForm {
  /**
   * 画面処理ID
   * @memberOf SimpleCrudDetailForm
   */
  #functionId;
  /**
   * リクエストURLs
   * @memberOf SimpleCrudDetailForm
   */
  #requestUrls = {};
  /**
   * 更新時用キー情報
   * @memberOf SimpleCrudDetailForm
   */
  #updateKey = {
    id: '',
    ol_updated_at: ''
  };
  /**
   * 一覧画面に戻る際、Gridを再表示する必要があるかどうかのフラグ
   * @memberOf SimpleCrudDetailForm
   */
  #refreshGrid = false;

  /**
   * コンストラクタ
   *
   * @param {string} functionId 画面処理ID
   * @param {object} requestUrls リクエストURL
   * @desc 詳細画面で発火するイベントの設定を行う。
   */
  constructor(functionId, requestUrls = {}) {
    super(`#form-${functionId}-detail`);
    this.#functionId = functionId;
    this.#requestUrls = requestUrls;
    this.setUpFormObserver();
    /**
     * 一覧画面から新規登録画面表示発火イベント
     */
    $(`#${this.#functionId}-detail-area`).on(
      'emit-simple-crud-detail-new-show',
      (e, param) => {
        this.resetForm();
        this.preNewInitialize(param);
        $(`#${this.#functionId}-btn-store`).show();
        $(`#${this.#functionId}-btn-update`).hide();
        $(`#${this.#functionId}-btn-delete`).hide();
        AnimateService.forward(`${this.#functionId}-detail-area`);
      }
    );
    /**
     * 一覧画面から更新画面表示発火イベント
     */
    $(`#${this.#functionId}-detail-area`).on(
      'emit-simple-crud-detail-update-show',
      (e, param) => {
        this.resetForm();
        this.preEditInitialize(param);
        $(`#${this.#functionId}-btn-store`).hide();
        $(`#${this.#functionId}-btn-update`).show();
        // $(`#${this.#functionId}-btn-delete`).hide();

        const url = StringService.bindParameter(this.#requestUrls.edit, param);
        const xhrParam = new XhrParam(url);
        const successHandler = (res) => {
          this.#updateKey.id = res.data.id;
          this.#updateKey.ol_updated_at = res.data.updated_at;
          this.bindValue(res.data);
          this.afterEditInitialize(res);
          AnimateService.forward(`${this.#functionId}-detail-area`);
        };
        XHRCrudMethod.get(xhrParam, successHandler);
      }
    );
    /**
     * 一覧画面から削除画面表示発火イベント
     */
    $(`#${this.#functionId}-detail-area`).on(
      'emit-simple-crud-detail-delete-show',
      (e, param) => {
        this.resetForm();
        this.preDeleteInitialize(param);
        $(`#${this.#functionId}-btn-store`).hide();
        $(`#${this.#functionId}-btn-update`).hide();
        $(`#${this.#functionId}-btn-delete`).show();

        const url = StringService.bindParameter(this.#requestUrls.edit, param);
        const xhrParam = new XhrParam(url);
        const successHandler = (res) => {
          this.#updateKey.id = res.data.id;
          this.#updateKey.ol_updated_at = res.data.updated_at;
          this.bindValue(res.data);
          this.afterDeleteInitialize(res);
          AnimateService.forward(`${this.#functionId}-detail-area`);
        };
        XHRCrudMethod.get(xhrParam, successHandler);
      }
    );
    /**
     * 登録ボタン押下
     */
    $(`#${this.#functionId}-btn-store`).on('click', (e) => {
      //入力チェック
      super.validate();

      if (!this.preStore()) {
        return;
      }

      ModalMessage.showModifyConfirm(
        ModalMessage.MODIFY_MODE.INS,
        null,
        this.doRegist
      );
    });
    /**
     * 更新ボタン押下
     */
    $(`#${this.#functionId}-btn-update`).on('click', (e) => {
      //入力チェック
      super.validate();
      if (!this.preUpdate()) {
        return;
      }

      ModalMessage.showModifyConfirm(
        ModalMessage.MODIFY_MODE.UPD,
        null,
        this.doUpdate
      );
    });
    /**
     * 削除ボタン押下
     */
    $(`#${this.#functionId}-btn-delete`).on('click', (e) => {
      if (!this.preDelete()) {
        return;
      }
      ModalMessage.showModifyConfirm(
        ModalMessage.MODIFY_MODE.DEL,
        null,
        this.doDelete
      );
    });
    /**
     * 戻るボタン押下
     */
    $(`#${this.#functionId}-btn-back`).on('click', (e) => {
      const back = () => {
        this.resetFormObserver();
        $(`#${this.#functionId}-list-area`).trigger(
          'emit-simple-crud-list-show',
          {refresh: this.#refreshGrid}
        );
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
   * 登録処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   */
  doRegist = () => {
    const xhrParam = new XhrParam(
      this.#requestUrls.store,
      this.createParameter()
    );
    const successhandler = (res) => {
      this.#updateKey.id = res.id;
      this.#updateKey.ol_updated_at = res.updated_at;
      this.resetFormObserver();
      this.#refreshGrid = true;
      $(`#${this.#functionId}-btn-store`).hide();
      $(`#${this.#functionId}-btn-update`).show();
      const triggerParam = this.afterStore(res);

      $(`#${this.#functionId}-list-area`).trigger(
        'emit-simple-crud-stored-data',
        triggerParam
      );
    };

    const keys = Object.keys(xhrParam.params);
    if (keys.includes('filedata')) {
      XHRCrudMethod.upload(xhrParam, successhandler);
    } else {
      XHRCrudMethod.store(xhrParam, successhandler);
    }
  };
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
      this.#refreshGrid = true;
      this.afterUpdate(res);
      $(`#${this.#functionId}-list-area`).trigger(
        'emit-simple-crud-updated-data',
        res
      );
    };

    const keys = Object.keys(xhrParam.params);
    if (keys.includes('filedata')) {
      XHRCrudMethod.upload(xhrParam, successHandler);
    } else {
      XHRCrudMethod.update(xhrParam, successHandler);
    }
  };
  /**
   * アップロードつき更新処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   */
  doUpload = () => {
    const requestParameter = this.createUpdateParameter();

    const url = StringService.bindParameter(
      this.#requestUrls.update,
      this.#updateKey
    );
    const xhrParam = new XhrParam(url, requestParameter);
    const successHandler = (res) => {
      this.#updateKey.ol_updated_at = res.updated_at;
      this.resetFormObserver();
      this.#refreshGrid = true;
      this.afterUpdate(res);
      $(`#${this.#functionId}-list-area`).trigger(
        'emit-simple-crud-updated-data',
        res
      );
    };
    XHRCrudMethod.upload(xhrParam, successHandler);
  };
  /**
   * 削除処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   */
  doDelete = () => {
    const requestParam = this.createDeleteParameter({
      ol_updated_at: this.#updateKey.olUpdatedAt
    });
    const url = StringService.bindParameter(
      this.#requestUrls.delete,
      this.#updateKey
    );
    const xhrParam = new XhrParam(url, requestParam);
    const successHandler = (res) => {
      this.resetFormObserver();
      this.afterDelete(res);
      $(`#${this.#functionId}-list-area`).trigger('emit-simple-crud-deleted');
      $(`#${this.#functionId}-btn-back`).trigger('click');
    };
    XHRCrudMethod.delete(xhrParam, successHandler);
  };
  /**
   * 新規登録時、初期表示の前処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @param {object} param 新規初期表示パラメーター
   */
  preNewInitialize(param) {
    this.changeDisabled(this.formSelector, false);
  }
  /**
   * 更新時、初期表示の前処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @param {object} param 更新初期表示パラメーター
   */
  preEditInitialize(param) {
    this.changeDisabled(this.formSelector, false);
  }
  /**
   * 更新時、初期表示の後処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @param {object} res レスポンス
   */
  afterEditInitialize(res) {}
  /**
   * 削除時、初期表示の前処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @param {object} param 更新初期表示パラメーター
   */
  preDeleteInitialize(param) {
    this.changeDisabled(this.formSelector, true);
  }
  /**
   * 削除時、初期表示の後処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @param {object} res レスポンス
   */
  afterDeleteInitialize(res) {}
  /**
   * 登録の前処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @return {bool} 処理結果
   */
  preStore() {
    return !this.hasError();
  }
  /**
   * 登録時の後処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @param {object} レスポンス
   * @return {object} リクエストパラメータ(json)
   */
  afterStore(res) {
    return res;
  }
  /**
   * 更新時の前処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @return {bool} 処理結果
   */
  preUpdate() {
    return !this.hasError();
  }
  /**
   * 更新時の後処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @param {object} レスポンス
   */
  afterUpdate(res) {}
  /**
   * 削除時のリクエストパラメーターを作成する。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @param {object} appendParam 削除処理固有のパラメータ定義
   * @return {object} リクエストパラメータ(json)
   */
  createDeleteParameter(appendParam = {}) {
    const json = {};
    Object.assign(json, appendParam);
    return json;
  }
  /**
   * 削除時の前処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   */
  preDelete() {
    return true;
  }
  /**
   * 削除時の後処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   */
  afterDelete() {}
  /**
   * 登録・更新時のリクエストパラメーターを作成する。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @return {object} リクエストパラメータ(json)
   */
  createParameter() {
    const param = this.formValueToJson();
    $(`#form-${this.#functionId}-detail [data-html-only]`).each(
      (index, element) => {
        delete param[element.id];
      }
    );

    Object.assign(param, this.createCustomParameter());

    return param;
  }

  /**
   * 更新時のリクエストパラメーターを作成する。
   * @return {object} リクエストパラメータ(json)
   */
  createUpdateParameter() {
    const param = this.formValueToJson();
    param['ol_updated_at'] = this.#updateKey.ol_updated_at;
    $(`#form-${this.#functionId}-detail [data-html-only]`).each(
      (index, element) => {
        delete param[element.id];
      }
    );

    Object.assign(param, this.createCustomParameter());
    return param;
  }
  /**
   * カスタム属性追加用
   */
  createCustomParameter() {
    return {};
  }

  /**
   * 機能IDを取得する。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @return 機能ID
   */
  get functionId() {
    return this.#functionId;
  }
  /**
   * リクエストURLを取得する。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @return 設定されているURL
   */
  get requestUrls() {
    return this.#requestUrls;
  }
  /**
   * リクエストURLの設定を更新する。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @param {object} requestUrls リクエストURL定義
   */
  setRequestUrls(requestUrls) {
    this.#requestUrls = requestUrls;
  }
  /**
   * リクエストURLを設定する。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @param {string} key リクエスト(edit/store/update/delete)
   * @param {string} url URL
   */
  setRequestUrl(key, url) {
    this.#requestUrls[key] = url;
  }
  /**
   * Gridを再表示する必要があるかどうかのフラグを取得する。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @return Gridを再表示する必要があるかどうかのフラグ
   */
  get refreshGrid() {
    return this.#refreshGrid;
  }
}
export {SimpleCrudDetailForm};
