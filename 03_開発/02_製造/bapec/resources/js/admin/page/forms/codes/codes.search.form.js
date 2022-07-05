import GridService from '@js/service/grid';
import {SimpleCrudSearchForm} from '@js/app/page/forms/simple.crud.search.form';

/**
 * コード検索画面クラス
 */
class CodesSearchForm extends SimpleCrudSearchForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      search: '/api/admin/codes/search',
      delete: '/api/admin/codes/:id'
    };

    super(functionId, requestUrls);
    this.defaultPagesSettings.sortItem = 'value';
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
      GridService.getColDefine({headerName: '値', field: 'value', width: 50})
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '内容',
        field: 'description',
        width: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '備考',
        field: 'remark',
        width: 100
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '属性1説明',
        field: 'attr_1_description',
        width: 60
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '属性1',
        field: 'attr_1',
        width: 60
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '属性2説明',
        field: 'attr_2_description',
        width: 60
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '属性2',
        field: 'attr_2',
        width: 60
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '属性3説明',
        field: 'attr_3_description',
        width: 60
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '属性3',
        field: 'attr_3',
        width: 60
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '表示順',
        field: 'sequence',
        width: 40,
        cellClass: ['text-right']
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

  /**
   * フォームをリセットする。
   */
  resetForm() {
    super.resetForm();
    // フォームリセット後に、コード入力欄のchangeイベントを発生させる事でコードのセレクトボックスをリセットする
    $('#search-input-code').trigger('change');
  }
}
export default CodesSearchForm;
