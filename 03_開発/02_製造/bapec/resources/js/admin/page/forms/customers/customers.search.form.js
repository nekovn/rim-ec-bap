import GridService from '@js/service/grid';
import {SimpleCrudSearchForm} from '@js/app/page/forms/simple.crud.search.form';

/**
 * 顧客検索画面クラス
 */
class CustomersSearchForm extends SimpleCrudSearchForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      search: '/api/admin/customers/search',
      delete: '/api/admin/customers/:id'
    };

    super(functionId, requestUrls);
    this.defaultPagesSettings.sortItem = 'id';
    this.defaultPagesSettings.sortOrder = 'desc';
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
        headerName: '顧客ID',
        field: 'id',
        sizeColumnsToFit: true,
        width: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({headerName: '姓名', field: 'full_name'})
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '姓名(カナ)',
        field: 'full_name_kana'
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: 'メールアドレス',
        field: 'email'
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '会員ランク',
        field: 'rank_name'
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
export default CustomersSearchForm;
