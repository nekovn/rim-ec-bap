import BaseForm from '@js/app/page/forms/base.form';
import AnimateService from '@js/service/animate';
import GridService from '@js/service/grid';
import {ModalMessage, Toaster} from '@js/service/notifications';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import StringService from '@js/service/string';

/**
 * simple.crudテンプレートの検索Form。R, Dの機能を提供する。<br>
 * 削除機能は、検索画面で削除を行いたい場合用。
 * @extends {BaseForm}
 * @classdesc path: @js/base/forms.simple.crud.search.form.js
 */
class SimpleCrudSearchForm extends BaseForm {
  /**
   * 画面処理ID
   * @memberOf SimpleCrudSearchForm
   */
  #functionId;
  /**
   * リクエストURLs
   * @memberOf SimpleCrudSearchForm
   */
  #requestUrls;
  /**
   * 一覧部のインスタンス
   * @memberOf SimpleCrudSearchForm
   */
  #gridInstance = null;
  /**
   * 一覧部の列定義 通常のクラス変数にする
   * @memberOf SimpleCrudSearchForm
   */
  // #gridColDefine;
  /**
   * 改ページ設定
   * @memberOf SimpleCrudSearchForm
   */
  #pagesSettings = {
    page: 1,
    sortItem: '',
    sortOrder: 'asc'
  };
  /**
   * コンストラクタ
   *
   * @param {string} functionId 機能ID
   * @param {object} requestUrls リクエストURL
   * @desc 検索画面で発火するイベントの設定を行う。
   */
  constructor(functionId, requestUrls) {
    super(`#form-${functionId}-searchcondition`);
    this.#functionId = functionId;
    this.#requestUrls = requestUrls;
    const gridColDefine = this.getGridColDefine();
    this.gridColDefine = new SimpleCrudGridColDefine(gridColDefine);

    this.defaultPagesSettings = {
      sortItem: '',
      sortOrder: 'asc'
    };

    /**
     * 新規登録ボタン押下
     */
    $(`#${this.#functionId}-btn-create`).on('click', (e) => {
      $(`#${this.#functionId}-detail-area`).trigger(
        'emit-simple-crud-detail-new-show',
        [{option: $(e.target).data('option')}]
      );
    });
    /**
     * 検索ボタン押下
     */
    $(`#${this.#functionId}-btn-search`).on('click', (e) => {
      this.setPageSettingsDefault();
      // this.#pagesSettings = {
      //   page: 1,
      //   sortItem: this.defaultPagesSettings.sortItem,
      //   sortOrder: this.defaultPagesSettings.sortOrder
      // };
      if (this.#gridInstance) {
        this.#gridInstance.clearSortIcon(
          this.#pagesSettings.sortItem,
          this.#pagesSettings.sortOrder
        );
      }
      this.doSearch();
    });
    /**
     * クリアボタン押下
     */
    $(`#${this.#functionId}-btn-clear`).on('click', (e) => {
      // ModalMessage.showConfirm(`<p>${fw.i18n.messages.C_0004}</p>`, UserList.clear);
      this.clearForm();
    });
    /**
     * グリッドサービスからの改ページ発火イベント
     */
    $(`#${this.#functionId}-grid-pagination`).on(
      'emit-page-change',
      (e, page) => {
        this.#pagesSettings.page = page;
        this.doSearch();
      }
    );
    /**
     * グリッドサービスからのソート発火イベント
     */
    $(`#${this.#functionId}-grid`).on('emit-grid-sort', (e, emitParam) => {
      this.#pagesSettings.page = 1;
      this.#pagesSettings.sortItem = emitParam.field;
      this.#pagesSettings.sortOrder = emitParam.order;
      this.doSearch();
    });
    /**
     * グリッドサービスからの一覧表示完了発火イベント
     */
    $(`#${this.#functionId}-list-area`).on(
      'emit-grid-renderd',
      (e, rowData) => {
        this.afterRenderGrid(rowData);
      }
    );
    /**
     * 一覧部編集ボタンクリック
     */
    $(`#${this.#functionId}-grid`).on('click', '.btn-edit', (e) => {
      this.doEditShow({data: {id: $(e.target).data('id')}});
    });
    /**
     * 一覧部削除ボタンクリック
     */
    $(`#${this.#functionId}-grid`).on('click', '.btn-delete', (e) => {
      if (!this.preDelete()) {
        return;
      }
      const callback = () => {
        const param = {
          id: $(e.target).data('id'),
          olUpdatedAt: $(e.target).data('ol-updated-at'),
          option: $(e.target).data('option')
        };
        this.doDelete(param);
      };
      if (fw.config.simpleTemplate.crud.delete === 'list') {
        ModalMessage.showModifyConfirm(
          ModalMessage.MODIFY_MODE.DEL,
          null,
          callback
        );
      } else {
        $(`#${this.#functionId}-detail-area`).trigger(
          'emit-simple-crud-detail-delete-show',
          {
            id: $(e.target).data('id')
          }
        );
      }
    });
    /**
     * 詳細画面から新規登録完了発火イベント
     */
    $(`#${this.#functionId}-list-area`).on(
      'emit-simple-crud-stored-data',
      (e, param = {}) => {
        if (this.isPageRefreshBack()) {
          return;
        }
        if (!this.#gridInstance) {
          const gridParam = {
            total: 1,
            data: []
          };
          this.renderGrid(gridParam);
        }
        this.afterStoredData(param);
        this.#gridInstance.appendRows([param]);
      }
    );
    /**
     * 詳細画面から更新完了発火イベント
     */
    $(`#${this.#functionId}-list-area`).on(
      'emit-simple-crud-updated-data',
      (e, param = {}) => {
        if (this.isPageRefreshBack() || !this.#gridInstance) {
          return;
        }

        this.#gridInstance.updateSelectedRow(param);
      }
    );
    /**
     * 詳細画面から削除完了発火イベント
     */
    $(`#${this.#functionId}-list-area`).on('emit-simple-crud-deleted', () => {
      if (this.isPageRefreshBack() || !this.#gridInstance) {
        return;
      }
      this.#gridInstance.deleteSelectedRow();
    });
    /**
     * 詳細画面から一覧画面表示発火イベント
     */
    $(`#${this.#functionId}-list-area`).on(
      'emit-simple-crud-list-show',
      (e, param = {}) => {
        this.preBack(param);
        AnimateService.backward(`${this.#functionId}-list-area`);
        if (this.isPageRefreshRealtime()) {
          return;
        } else if (param.refresh) {
          this.doSearch();
        }
      }
    );
  }
  /**
   * 一覧の列定義を取得する。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @return {array} 列定義体
   */
  getGridColDefine() {
    return [];
  }
  /**
   * 検索時の前処理を行う。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @return {bool} 処理結果
   */
  preSearch() {
    return true;
  }
  /**
   * フォームをリセットする。
   * @function
   * @memberOf SimpleCrudSearchForm
   */
  resetForm() {
    super.resetForm();
  }
  /**
   * 戻るなどでsession値から検索したい場合、パラメータを設定する
   * 検索と一覧が別画面の場合
   * @param {}} page
   * @param {*} sortItem
   * @param {*} sortOrder
   */
  setSearchPage() {
    const grid = document.getElementById(`${this.#functionId}-grid`);
    if (grid.dataset.opage) {
      this.#pagesSettings.page = Number(grid.dataset.opage);
    }
    if (grid.dataset.oitem) {
      this.#pagesSettings.sortItem = grid.dataset.oitem;
    }
    if (grid.dataset.oorder) {
      this.#pagesSettings.sortOrder = grid.dataset.oorder;
    }
  }
  /**
   * 検索条件を設定する
   */
  setPageSettings(page, item, order) {
    this.#pagesSettings = {
      page: page,
      sortItem: item,
      sortOrder: order
    };
  }
  /**
   * 検索条件を設定する
   */
  setPageSettingsDefault() {
    this.setPageSettings(
      1,
      this.defaultPagesSettings.sortItem,
      this.defaultPagesSettings.sortOrder
    );
  }
  /**
   * 検索処理を行う。
   * @function
   * @memberOf SimpleCrudSearchForm
   */
  doSearch() {
    if (!this.preSearch()) {
      return;
    }
    const requestParameter = {
      form: this.createSearchParameter(),
      page: {
        count: $(`#${this.#functionId}-display-count`).val(),
        page: this.#pagesSettings.page,
        sortItem: this.#pagesSettings.sortItem,
        sortOrder: this.#pagesSettings.sortOrder
      }
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
   * 検索時のリクエストパラメーターを作成する。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @return {object} リクエストパラメータ(json)
   */
  createSearchParameter() {
    return this.formValueToJson('search-');
  }
  /**
   * 一覧部描画の前処理を行う。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @param {object} res 検索結果
   * @return {object} 検索結果を維持するオブジェクト
   */
  preRenderGrid(res) {
    return res;
  }
  /**
   * agグリッドのカスタムオプションを設定する。
   * @function
   * @return {array} agグリッドオプション
   */
  getCustomGridOptions() {
    return [];
  }
  /**
   * 一覧部の再描画を行う。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @param {object} res 検索結果
   */
  renderGrid(res) {
    // 描画前処理
    const rowData = this.preRenderGrid(res);
    // 件数選択表示
    const area = document.getElementById(`${this.functionId}-search-result`);
    area.classList.remove('d-none');

    // 描画準備
    if (!this.#gridInstance) {
      //初期表示のときデフォルトソートがあれば設定する
      this.gridColDefine.colDefines.forEach((def) => {
        if (def.field == this.#pagesSettings.sortItem) {
          def.sort = this.#pagesSettings.sortOrder;
        }
      });
      this.#gridInstance = new GridService(`${this.#functionId}-grid`);
    }
    // 描画
    const columnDefs = this.getColDefine();
    let gridOptions = {onRowDoubleClicked: this.doEditShow};

    Object.assign(gridOptions, this.getCustomGridOptions());
    let dispCount = {
      total: res.total,
      page: this.#pagesSettings.page,
      limit: $(`#${this.#functionId}-display-count`).val()
    };
    this.#gridInstance.render(columnDefs, rowData.data, gridOptions, dispCount);
    this.#gridInstance.setupPagination(
      rowData.total,
      $(`#${this.#functionId}-display-count`).val(),
      this.#pagesSettings.page
    );
  }
  /**
   * 一覧部の列定義体を取得する。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @return {array} 列定義
   */
  getColDefine() {
    let columnDefs = [];
    if (this.gridColDefine.useEdit) {
      columnDefs.push(
        GridService.getColDefine({
          minWidth: 60,
          maxWidth: 60,
          sortable: false,
          cellStyle: {cursor: 'pointer'},
          cellRenderer: (params) =>
            `<i class="fas fa-pencil-alt btn-edit" data-id="${params.data.id}" title="編集"></i>`
        })
      );
    }
    this.gridColDefine.appendEditCellDefs.forEach((def) =>
      columnDefs.push(def)
    );
    columnDefs = columnDefs.concat(this.gridColDefine.colDefines);
    if (this.gridColDefine.useDelete) {
      columnDefs.push(
        GridService.getColDefine({
          minWidth: 60,
          maxWidth: 60,
          sortable: false,
          cellStyle: {cursor: 'pointer'},
          cellRenderer: (params) =>
            `<i class="far fa-trash-alt btn-delete" data-id="${params.data.id}" data-ol-updated-at="${params.data.updated_at}" title="削除"></i>`
        })
      );
    }
    this.gridColDefine.appendDeleteCellDefs.forEach((def) =>
      columnDefs.push(def)
    );
    return columnDefs;
  }
  /**
   * 一覧表示の後処理を行う。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @param {Object} res 行データ
   */
  afterRenderGrid(res) {}
  /**
   * 編集画面を表示する。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @param {object} row 行データ
   */
  doEditShow = (row) => {
    let id = '-1';
    if (row && row.data) {
      id = row.data.id;
    } else {
      id = row;
    }
    $(`#${this.#functionId}-detail-area`).trigger(
      'emit-simple-crud-detail-update-show',
      {id: id}
    );
  };
  /**
   * 削除処理を行う。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @param {object} 削除パラメーター
   */
  doDelete = (param) => {
    const requestParameter = this.createDeleteParameter(param);
    const url = StringService.bindParameter(this.#requestUrls.delete, param);
    const xhrParam = new XhrParam(url, requestParameter);
    const successHandler = (res) => {
      this.afterDelete(res);
      if (this.isPageRefreshRealtime()) {
        this.#gridInstance.deleteSelectedRow();
      } else {
        $(`#${this.#functionId}-btn-back`).trigger('click');
      }
    };
    XHRCrudMethod.delete(xhrParam, successHandler);
  };
  /**
   * 削除時のリクエストパラメーターを作成する。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @return {object} リクエストパラメータ(json)
   */
  createDeleteParameter(param) {
    return {
      ol: {
        updated_at: param.olUpdatedAt
      },
      option: param.option
    };
  }
  /**
   * 削除前処理
   * @function
   * @memberOf SimpleCrudSearchForm
   * @retur {bool} true: 正常, false: 異常
   */
  preDelete() {
    return true;
  }
  /**
   * 削除後処理
   * @function
   * @memberOf SimpleCrudSearchForm
   */
  afterDelete() {}
  /**
   * 登録後処理
   * @function
   * @memberOf SimpleCrudSearchForm
   * @return {object} 登録時のレスポンス
   */
  afterStoredData(param) {
    return param;
  }
  /**
   * 戻る前処理
   * @function
   * @memberOf SimpleCrudSearchForm
   * @return {object} パラメータ
   */
  preBack(param) {
    return param;
  }
  /**
   * 一覧部の更新が戻るのタイミングか
   * @return {bool} true: 戻る, false: リアルタイム
   */
  isPageRefreshBack() {
    return fw.config.simpleTemplate.crud['page-refresh'] === 'back';
  }
  /**
   * 一覧部の更新がリアルタイムか
   * @return {bool} true: リアルタイム, false: 戻る
   */
  isPageRefreshRealtime() {
    return fw.config.simpleTemplate.crud['page-refresh'] === 'realtime';
  }
  /**
   * リクエストURLの設定を取得する。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @return {object} リクエストURL定義
   */
  get requestUrls() {
    return this.#requestUrls;
  }
  /**
   * リクエストURLの設定を更新する。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @param {object} requestUrls リクエストURL定義
   */
  setRequestUrls(requestUrls) {
    this.#requestUrls = requestUrls;
  }
  /**
   * リクエストURLの設定を更新する。
   * @function
   * @memberOf SimpleCrudSearchForm
   * @param {string} key キー
   * @param {string} url URL
   */
  setRequestUrl(key, url) {
    this.#requestUrls[key] = url;
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
   * グリッドのインスタンスを取得する。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @return グリッドインスタンス
   */
  get gridInstance() {
    return this.#gridInstance;
  }
  /**
   * Agグリッドのインスタンスを取得する。
   * @function
   * @memberOf SimpleCrudDetailForm
   * @return Agグリッドインスタンス
   */
  get agGridInstance() {
    if (!this.#gridInstance) {
      return null;
    }
    return this.#gridInstance.instance;
  }
}
/**
 * simple.crud.searchを用いて一覧を表示する際の列定義体クラス
 * @classdesc path: @js/base/forms.simple.crud.search.form.js
 */
class SimpleCrudGridColDefine {
  /**
   * 列定義体
   * @function
   * @memberOf SimpleCrudGridColDefine
   */
  #colDefines = [];
  /**
   * 編集列使用フラグ
   * @function
   * @memberOf SimpleCrudGridColDefine
   */
  useEdit = fw.config.simpleTemplate.crud.list.grid['use-edit-column'];
  /**
   * 編集列に追加で表示する列定義体
   * @function
   * @memberOf SimpleCrudGridColDefine
   */
  appendEditCellDefs = [];
  /**
   * 削除列使用フラグ
   * @function
   * @memberOf SimpleCrudGridColDefine
   */
  useDelete = fw.config.simpleTemplate.crud.list.grid['use-delete-column'];
  /**
   * 削除列に追加で表示する列定義帯
   * @function
   * @memberOf SimpleCrudGridColDefine
   */
  appendDeleteCellDefs = [];
  /**
   * コンストラクター
   * @param {object} colDefines ヘッダー定義
   */
  constructor(colDefines = []) {
    this.#colDefines = colDefines;
  }
  /**
   * 列定義を返す。
   * @function
   * @memberOf SimpleCrudGridColDefine
   * @return {array} 列定義
   */
  get colDefines() {
    return this.#colDefines;
  }
}
export {SimpleCrudSearchForm, SimpleCrudGridColDefine};
