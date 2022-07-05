import OrderDetailsForm from '@js/admin/page/forms/orders/order.details.form.js';

/**
 * 受注詳細ページクラス
 */
class OrderDetailsPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    new OrderDetailsForm(functionId);
  }
}
new OrderDetailsPage('order-details');
