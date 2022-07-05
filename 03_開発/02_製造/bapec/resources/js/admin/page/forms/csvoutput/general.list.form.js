import GridService from '@js/service/grid';
import BaseForm from '@js/app/page/forms/base.form';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import {Toaster} from '@js/service/notifications';
import Messages from '@js/app/messages';

/**
 * 汎用一覧
 */
class GeneralListForm extends BaseForm {
  // 機能ID
  #functionId;
  #requestUrls;
  // 一覧部の定義
  // #gridColDefines;
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID functionIdは、各bladeのfunctionIdを設定する
   * @param isDialog true:ダイアログ表示
   */
  constructor(functionId) {
    super(`#form-${functionId}`);
    this.#functionId = functionId;
    this.gridInstance = null;
    this.pageSize = 50;
    this.#requestUrls = {
      search: '/api/admin/general_list/search'
    };
    //メニューボタン削除
    $('header .c-header-toggler').remove();

    this.gridOptions = {
      // 行の高さ
      rowHeight: 31,
      headerHeight: 35,
      resizable: true,
      // コンテンツの高さ自動
      // domLayout: 'autoHeight',
      defaultColDef: {
        sortable: true,
        resizable: true,
        filter: true,
        width: 100,
        maxWidth: 260,
        enableRowGroup: true,
        enablePivot: true,
        enableValue: true
      },
      rowSelection: 'single',
      // ページネーション
      pagination: true,
      // suppressPaginationPanel: true,
      // suppressScrollOnNewData: true,
      paginationPageSize: this.pageSize,
      //   paginationChanged: (e, page) => {
      //     $(selector).trigger('emit-page-change', page);
      //   },
      localeText: AG_GRID_LOCALE_JA,
      // rowGroupPanelShow: 'always',
      // pivotPanelShow: 'always',
      rowData: null
    };
    //CSV DL
    $(`#${this.#functionId}-btn-download`).on('click', (e) => {
      this.onBtnExportDataAsCsv();
    });

    /**
     * グリッドサービスからの改ページ発火イベント
     */
    $(`#${this.#functionId}-grid-pagination`).on(
      'emit-page-change',
      (e, page) => {
        this.doSearch(e, page);
      }
    );

    this.doSearch(null, 1);
  }
  doSearch(e, page) {
    //検索
    this.params = JSON.parse(
      window.opener.document.getElementsByName('generallist-param')[0].value
    );
    document.getElementById('list-title').textContent =
      this.params['joken_file_name'];
    window.opener.document.getElementsByName('generallist-param')[0].value =
      null; //hidden項目をクリアする
    this.params.char_code = 0; //画面表示はUTF-8 限定
    const xhrParam = new XhrParam(this.#requestUrls.search, this.params);
    const successHandler = (res) => this.renderGrid(page, res);
    XHRCrudMethod.get(xhrParam, successHandler);
  }

  /**
   * grid表示
   * @param {} res
   */
  renderGrid(page, res) {
    $(`#${this.#functionId}-btn-download`).addClass('d-none');
    // 件数チェック
    if (Number(res.total) > 0 && res.data.length == 0) {
      Toaster.showWarningToaster(
        Messages.getMessage('E.search.limitover', {max: res.total}),
        5000
      );
      return;
    }

    // 描画準備
    if (!this.gridInstance) {
      const gridDiv = document.querySelector(`#${this.#functionId}-grid`);
      const instance = new agGrid.Grid(gridDiv, this.gridOptions);
      this.gridInstance = new GridService(
        `${this.#functionId}-grid`,
        null,
        instance
      );
    }

    $('#total-count').html('全 ' + res.total.toLocaleString() + ' 件');

    //列の設定
    this.gridOptions.columnApi.resetColumnState();

    if (res.data.length == 0) {
      // this.gridInstance.render([], res.data, this.gridOptions);
      this.gridOptions.api.setRowData(res.data);
      return;
    }
    //ヘッダ
    let colDefines = [];
    Object.keys(res.data[0]).forEach(function (header) {
      colDefines.push({field: header, tooltipField: header});
    });

    this.gridOptions.api.setColumnDefs(colDefines);

    this.gridOptions.api.setRowData(res.data);
    let allColumnIds = [];
    this.gridOptions.columnApi.getAllColumns().forEach((column) => {
      allColumnIds.push(column.colId);
    });
    this.gridOptions.columnApi.autoSizeColumns(allColumnIds);

    //page
    $(`#${this.#functionId}-btn-download`).removeClass('d-none');
  }
  /**
   * CSV Download
   */
  onBtnExportDataAsCsv() {
    const params = this.getParams();
    this.gridOptions.api.exportDataAsCsv(params);
  }
  getParams() {
    return {
      suppressQuotes: false,
      columnSeparator: false,
      customHeader: false,
      customFooter: false,
      fileName: this.params['joken_file_name']
    };
  }
}
export default GeneralListForm;
