import GridService from '@js/service/grid';
import {SimpleCrudSearchForm} from '@js/app/page/forms/simple.crud.search.form';

/**
 * ユーザー検索画面クラス
 */
class UsersSearchForm extends SimpleCrudSearchForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      search: '/api/admin/users/search',
      delete: '/api/admin/users/:id'
    };

    //権限編集クリック
    $(document).on('click', '.auth-link', (e) => {
      $(`#${functionId}-detail-area2-username`).text(
        e.currentTarget.dataset.name
      );

      const id = e.currentTarget.dataset.id;
      $(`#${functionId}-detail-area2`).trigger(
        'emit-simple-crud-detail2-update-show',
        {id: id}
      );
    });

    super(functionId, requestUrls);
    this.defaultPagesSettings.sortItem = 'name';
  }
  /**
   * 一覧の列定義を取得する。
   * @function
   * @memberOf UsersSearchForm
   * @return {array} 列定義体
   */
  getGridColDefine() {
    let colDefines = [];
    colDefines.push(
      GridService.getColDefine({
        headerName: '社員番号',
        field: 'code',
        sizeColumnsToFit: true,
        width: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({headerName: '氏名', field: 'name'})
    );
    colDefines.push(
      GridService.getColDefine({
        sizeColumnsToFit: true,
        minWidth: 120,
        width: 120,
        maxWidth: 120,
        sortable: false,
        cellClass: ['pb-0', 'pt-0', 'grid-btn'],
        cellRenderer: (params) =>
          `<button type="button" data-link="${
            this.functionId
          }-detail-area2" class="btn btn-outline-dark btn-sm pt-0 pb-0 auth-link w-100" 
          data-id="${params.data.id}" data-name="${
            params.data.name == null ? '' : params.data.name
          }">権限編集</button>`
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
}
export default UsersSearchForm;
