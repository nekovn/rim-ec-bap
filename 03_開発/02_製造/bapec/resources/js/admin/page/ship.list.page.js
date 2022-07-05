import ShipListForm from '@js/admin/page/forms/ships/ship.list.form.js';

/**
 * 出荷一覧ページクラス
 */
class ShipListPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    new ShipListForm(functionId);
  }
}
new ShipListPage('ship-list');
