import GeneralListForm from '@js/admin/page/forms/csvoutput/general.list.form';
/**
 * 汎用リスト出力クラス
 */
class GeneralListPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.GeneralListForm = new GeneralListForm(functionId);
  }
}
new GeneralListPage('general_list');
