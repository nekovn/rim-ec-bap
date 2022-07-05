import GridService from '@js/service/grid';
import Dialog from '@js/service/dialog';
import StringService from '@js/service/string';
import {Toaster} from '@js/service/notifications';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import {ModalMessage} from '@js/service/notifications';
import BaseForm from '@js/app/page/forms/base.form.js';
import ProductsDialogForm from '../products/products.dialog.form';

class SandboxSearchForm extends BaseForm {
  // 機能ID
  #functionId;
  // リクエストURL
  #requestUrls;
  // 一覧部の定義
  #gridColDefines;
  // ダイアログの機能ID
  #clientsRefDialogId = 'dialog_xxx';
  // 更新時用キー情報
  #updateKey = {
    id: '',
    ol_updated_at: ''
  };

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    super(`#form-${functionId}-searchcondition`);
    this.#functionId = functionId;
    this.gridInstance = null;
    this.#requestUrls = {
      search: '/api/admin/sandbox/search',
      update: '/api/admin/sandbox/:id/products'
    };

    this.#updateKey.id = window.recommend.id;
    this.#updateKey.ol_updated_at = window.recommend.updated_at;

    // grid列定義
    let colDefines = [];
    colDefines.push(
      GridService.getColDefine({
        headerName: 'code',
        field: 'code',
        width: 100
      })
    );
    colDefines.push(
      GridService.getColDefine({headerName: 'name', field: 'name'})
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '削除',
        maxWidth: 60,
        width: 60,
        cellRenderer: (params) =>
          `<input type="checkbox" name="del-row-chk[]" data-nodeid="${params.node.id}" />`
      })
    );
    this.#gridColDefines = colDefines;

    // dialog初期化
    Dialog.settingDialog(
      `#${this.#clientsRefDialogId}-area`,
      'clientsRefDialog',
      []
    );
    // dialog表示
    $(`#${functionId}_xxx_ref_btn`).on('click', () => {
      $(`#${this.#clientsRefDialogId}-area`).dialog('open');
    });
    new ProductsDialogForm(this.#clientsRefDialogId);
    const clientsRefDialog = document.getElementById(
      `${this.#clientsRefDialogId}`
    );
    // dialogで選択処理の戻り
    clientsRefDialog.addEventListener('emit-dialog-row-selected', (e) => {
      this.gridInstance.appendRows([e.detail]);
    });

    /**
     * 削除ボタン押下
     */
    $(`#${this.#functionId}-btn-delete`).on('click', (e) => {
      if ($('input[name="del-row-chk[]"]:checked').length == 0) {
        ModalMessage.showDanger(fw.i18n.messages['I.select.target']);
        return;
      }
      ModalMessage.showConfirm(
        fw.i18n.messages['C.delete.selected.confirm'],
        () => this.doDelete(),
        null
      );
    });

    /**
     * 更新ボタン押下
     */
    $(`#${this.#functionId}-btn-update`).on('click', (e) => {
      ModalMessage.showModifyConfirm(ModalMessage.MODIFY_MODE.UPD, null, () =>
        this.doUpdate()
      );
    });

    // 初期表示で検索実行
    this.doSearch();
  }

  /**
   * 明細削除処理
   */
  doDelete() {
    const ids = $.map(
      $('input[name="del-row-chk[]"]:checked'),
      (elm) => $(elm)[0].dataset.nodeid
    );
    this.gridInstance.deleteRow(ids);
  }
  /**
   * 更新処理
   */
  doUpdate() {
    const ids = $.map(this.gridInstance.getAllRow(), (data) => data.id);

    const requestParameter = this.formValueToJson();
    Object.assign(requestParameter, {
      recommend_id: this.#updateKey.id,
      products_ids: ids
    });

    const url = StringService.bindParameter(
      this.#requestUrls.update,
      this.#updateKey
    );
    const xhrParam = new XhrParam(url, requestParameter);
    const successHandler = (res) => {
      this.#updateKey.ol_updated_at = res.updated_at;
    };
    XHRCrudMethod.update(xhrParam, successHandler);
  }
  /**
   * 検索処理を行う。
   * @function
   * @memberOf SimpleCrudSearchForm
   */
  doSearch() {
    const requestParameter = {
      form: this.formValueToJson('search-')
    };

    const xhrParam = new XhrParam(this.#requestUrls.search, requestParameter);
    const successHandler = (res) => {
      if (res.message) {
        Toaster.showWarningToaster(res.message);
      }
      this.renderGrid(res);
    };
    XHRCrudMethod.get(xhrParam, successHandler);
  }
  /**
   * grid描画
   * @param {*} res
   */
  renderGrid(res) {
    // 描画準備
    if (!this.gridInstance) {
      this.gridInstance = new GridService(`${this.#functionId}-grid`);
    }
    // 描画
    let gridOptions = {
      onRowDoubleClicked: (row) => {
        this.doSelect(row.data);
      }
    };
    this.gridInstance.render(this.#gridColDefines, res.data, gridOptions);
  }
}
export default SandboxSearchForm;
