import ShipDetailsForm from '@js/admin/page/forms/ships/ship.details.form.js';

/**
 * 出荷詳細ページクラス
 */
class ShipDetailsPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    new ShipDetailsForm(functionId);
  }
}
new ShipDetailsPage('ship-details');
