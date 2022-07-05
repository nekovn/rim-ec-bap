import ImportShipsAchieveForm from '@js/admin/page/forms/import/import.shipsachieve.form.js';

/**
 * 出荷実績取込ページクラス
 */
class ImportShipsAchievePage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.form = new ImportShipsAchieveForm(functionId);
  }
}
new ImportShipsAchievePage('importShipsAchieve');
