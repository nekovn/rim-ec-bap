import BaseForm from '@js/app/page/forms/base.form';

/**
 * 注文入力確認ページクラス
 */
class OrderConfirmPage extends BaseForm {
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
     * 注文入力完了
     */
    $(`#${this.#functionId}-btn-checkout`).on('click', (e) => {
      //注文チェックアウト
      //this.doCheckout();
      window.location.href = '/order/checkout';
    });

    /**
     * 注文入力画面
     */
    $(`#${this.#functionId}-btn-confirm`).on('click', (e) => {
      //注文入力画面へ遷移する。
      window.location.href = '/order/input';
    });
  }

  // /**
  //  * 注文入力完了を行う。
  //  * @function
  //  */

  doCheckout = () => {
    $(`#form-${this.#functionId}-checkout`).submit();
  };
}

new OrderConfirmPage('order-confirm');
