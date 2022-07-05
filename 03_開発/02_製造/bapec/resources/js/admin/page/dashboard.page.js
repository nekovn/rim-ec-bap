import DashBoardIndexForm from './forms/dashboard/dashboard.index.form';

/**
 * DashBoardページクラス
 */
class DashBoardPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.form = new DashBoardIndexForm(functionId);
  }
}
new DashBoardPage('dashboard');
