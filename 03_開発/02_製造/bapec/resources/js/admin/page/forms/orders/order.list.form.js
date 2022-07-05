import GridService from '@js/service/grid';
import {SimpleCrudSearchForm} from '@js/app/page/forms/simple.crud.search.form';
import StringService from '@js/service/string';

/**
 * 受注一覧画面クラス
 */
class OrderListForm extends SimpleCrudSearchForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      search: '/api/admin/orders/search',
      detail: '/admin/orders/:orderId/details'
    };

    super(functionId, requestUrls);
    this.defaultPagesSettings.sortItem = 'ordered_at';
    this.defaultPagesSettings.sortOrder = 'desc';

    //詳細からの戻りの時は検索をかける
    if ($(`#form-${functionId}-searchcondition`).data('is-back')) {
      this.setSearchPage();
      this.doSearch();
    }
  }

  /**
   * 一覧の列定義を取得する。
   * @function
   * @memberOf CustomersSearchForm
   * @return {array} 列定義体
   */
  getGridColDefine() {
    let colDefines = [];
    colDefines.push(
      GridService.getColDefine({
        headerName: '受注ID',
        field: 'id',
        // sizeColumnsToFit: true,
        width: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '受注日時',
        field: 'ordered_at',
        sizeColumnsToFit: false,
        width: 175
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '顧客ID',
        field: 'customer_id',
        // sizeColumnsToFit: true,
        width: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '顧客名',
        field: 'customer_name_full'
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '購入金額',
        field: 'total',
        cellClass: ['text-right'],
        valueFormatter: GridService.formatNumber
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '支払方法',
        field: 'payment_method_name'
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '決済ステータス',
        cellClass: ['text-center'],
        // field: 'payment_status_name',
        cellRenderer: (params) =>
          `<div class="mx-auto badge-status ${params.data.payment_status_style}">${params.data.payment_status_nm}</div>`
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '受注ステータス',
        cellClass: ['text-center'],
        // field: 'status_name',
        cellRenderer: (params) =>
          `<div class="mx-auto badge-status ${params.data.status_style}">${params.data.status_name}</div>`
      })
    );

    return colDefines;
  }

  /**
   * 検索前処理
   * @return {bool} 処理結果
   */
  preSearch() {
    return !this.hasError();
  }
  /** 編集画面遷移 */
  doEditShow = (row) => {
    let orderId = '-1';
    if (row) {
      orderId = row.data.id;
    }
    const url = StringService.bindParameter(this.requestUrls.detail, {
      orderId: orderId
    });
    window.location.href = url;
  };
}
export default OrderListForm;
