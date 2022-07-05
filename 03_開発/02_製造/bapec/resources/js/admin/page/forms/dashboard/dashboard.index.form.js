import BaseForm from '@js/app/page/forms/base.form';

/**
 * DashBoard画面クラス
 */
class DashBoardIndexForm extends BaseForm {
  /**
   * 画面処理ID
   * @memberOf DashBoardIndexForm
   */
  #functionId;
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    super(functionId);
    this.#functionId = functionId;
  }
}
export default DashBoardIndexForm;
