import BaseForm from '@js/app/page/forms/base.form';

/**
 * 購入履歴詳細ページクラス
 */
class orderHistoryDetailPage extends BaseForm {
  /**
   * 画面処理ID
   */
  #functionId;

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    super(`#form-${functionId}`);
    this.#functionId = functionId;

    /**
     * 注文キャンセルボタンクリック
     */
    $(`#order-history-detail-btn-cancel`).on('click', (e) => {
      var result = window.confirm('注文をキャンセルします。よろしいですか？');
      if (result) {
        const form = document.getElementById('form-order-history-detail');
        form.submit();
      } else {
        return false;
      }
    });
  }
}
new orderHistoryDetailPage('order-history-detail');
