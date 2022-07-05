import OrderListForm from '@js/admin/page/forms/orders/order.list.form.js';

/**
 * 受注一覧ページクラス
 */
class OrderListPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    new OrderListForm(functionId);
  }
}
new OrderListPage('order-list');
