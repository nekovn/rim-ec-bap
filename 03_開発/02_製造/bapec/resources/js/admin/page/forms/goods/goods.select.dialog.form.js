import GridService from '@js/service/grid';
import {SimpleCrudSearchForm} from '@js/app/page/forms/simple.crud.search.form';
import {Toaster} from '@js/service/notifications';
import Messages from '@js/app/messages';

/**
 * 商品選択画面クラス
 */
class GoodsSelectDialogForm extends SimpleCrudSearchForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      search: '/api/admin/goods/search'
    };

    super(functionId, requestUrls);
    this.defaultPagesSettings.sortItem = 'name';

    this.gridColDefine.useDelete = false;
    this.gridColDefine.useEdit = false;

    //選択クリック
    $(`#${this.functionId}-btn-select`).on('click', (e) => {
      let selNodes = [];
      if (this.agGridInstance) {
        selNodes = this.agGridInstance.gridOptions.api.getSelectedNodes();
      }
      if (selNodes.length == 0) {
        Toaster.showAlertToaster(
          Messages.getMessage('E.nosetting', {
            field: '商品'
          })
        );
        return false;
      }
      let goodsIds = [];
      for (let i = 0; i < selNodes.length; i++) {
        goodsIds.push(selNodes[i].data.id);
      }
      // $(`#${functionId}-area`).dialog('close');

      const event = new CustomEvent('selected', {detail: {ids: goodsIds}});
      //イベント発火
      document.getElementById(functionId).dispatchEvent(event);
    });
  }
  /**
   * グリッドを破棄する
   */
  gridDestroy() {
    if (this.gridInstance !== null) {
      this.gridInstance.destroy();
    }
  }
  /**
   * 一覧の列定義を取得する。
   * @function
   * @memberOf GoodsSearchForm
   * @return {array} 列定義体
   */
  getGridColDefine() {
    let colDefines = [];
    colDefines.push(
      GridService.getColDefine({
        checkboxSelection: true,
        width: 80,
        maxWidth: 80,
        sortable: false
      })
    );

    // colDefines.push(
    //   GridService.getColDefine({
    //     headerName: '商品代表画像',
    //     cellRenderer: (params) => {
    //       const path = params.data.image;
    //       if (path !== undefined && path !== null && path !== '') {
    //         const urlPath = path.replace('public', '/storage');
    //         return `<img src="${urlPath}" alt="商品代表画像" height="50">`;
    //       } else {
    //         return '';
    //       }
    //     }
    //   })
    // );
    colDefines.push(
      GridService.getColDefine({
        headerName: '商品コード',
        field: 'code',
        sizeColumnsToFit: true,
        width: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({headerName: '商品名', field: 'name'})
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '規格',
        field: 'volume',
        width: 150,
        maxWidth: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: 'メーカー',
        field: 'maker_name'
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '公開状況',
        field: 'published',
        width: 150,
        maxWidth: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '販売ステータス',
        field: 'sale_status_nm'
      })
    );

    return colDefines;
  }
  /**
   * agグリッドのカスタムオプションを設定する。
   * @function
   * @return {array} agグリッドオプション
   */
  getCustomGridOptions() {
    return {rowSelection: 'multiple'};
  }
}

export default GoodsSelectDialogForm;
