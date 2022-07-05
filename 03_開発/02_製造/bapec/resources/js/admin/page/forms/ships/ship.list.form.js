import GridService from '@js/service/grid';
import {SimpleCrudSearchForm} from '@js/app/page/forms/simple.crud.search.form';
import StringService from '@js/service/string';

/**
 * 出荷一覧画面クラス
 */
class ShipListForm extends SimpleCrudSearchForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      search: '/api/admin/ships/search',
      detail: '/admin/ships/:shipId/details'
    };

    super(functionId, requestUrls);
    this.defaultPagesSettings.sortItem = 'ship_direct_date';
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
        headerName: '出荷ID',
        field: 'id',
        // sizeColumnsToFit: true,
        width: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '受注ID',
        field: 'order_id',
        width: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '出荷指示日',
        field: 'ship_direct_date',
        sizeColumnsToFit: false,
        width: 175
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '出荷日',
        field: 'ship_date',
        // sizeColumnsToFit: true,
        width: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: 'ご注文者名',
        field: 'name_full'
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '送り状コメント',
        field: 'invoice_comment'
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '問合せ番号',
        field: 'slip_no'
      })
    );

    colDefines.push(
      GridService.getColDefine({
        headerName: '出荷ステータス',
        cellClass: ['text-center'],
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
    let shipId = '-1';
    if (row) {
      shipId = row.data.id;
    }
    const url = StringService.bindParameter(this.requestUrls.detail, {
      shipId: shipId
    });
    window.location.href = url;
  };
}
export default ShipListForm;
