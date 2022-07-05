import BaseForm from '@js/app/page/forms/base.form';
import OrderChangePayment from './forms/order/order.change.payment';
import OrderChangePoint from './forms/order/order.change.point';

/**
 * 注文入力情報ページクラス
 */
class OrderInputPage extends BaseForm {
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
    this.setUpFormObserver();
    new OrderChangePayment(functionId);
    new OrderChangePoint(functionId);

    /**
     * サロン変更時
     */
    $(`#${this.#functionId}-salon`).on('change', (e, clear = true) => {
      const salonId = e.currentTarget.value;
      // document.getElementsByName('bcrews_salon_short_name')[0].value =
      //   e.currentTarget.selectedOptions[0].dataset.sname;

      if (clear) {
        //staff選択をクリア
        $(`#${this.#functionId}-staff`).val('');
      }
      let staffs = document.querySelectorAll(
        `#${this.#functionId}-staff [data-salonid]`
      );
      for (let idx = 0; idx < staffs.length; idx++) {
        if (staffs[idx].dataset.salonid == salonId) {
          staffs[idx].classList.remove('d-none');
        } else {
          staffs[idx].classList.add('d-none');
        }
      }
    });
    /**
     * 確認ボタン押下
     */
    $(`#${this.#functionId}-btn-confirm`).on('click', (e) => {
      //入力チェック
      if (super.hasError()) {
        return;
      }

      const salon = $(`#${this.#functionId}-salon option:selected`);
      const salonName = salon.data().name;
      const salonShortName = salon.data().sname;
      const staff = $(`#${this.#functionId}-staff option:selected`);
      const staffName = staff.data().name;

      $('#bcrews_salon_name').val(salonName);
      $('#bcrews_salon_short_name').val(salonShortName);
      $('#bcrews_staff_name').val(staffName);

      this.doOrder();
    });

    /**
     * カートに戻るボタン押下
     */
    $(`#${this.#functionId}-btn-cart`).on('click', (e) => {
      //カート画面へ遷移する。
      window.location.href = '/cart';
    });

    $(`#${this.#functionId}-salon`).trigger('change', false);
  }

  // /**
  //  * 注文入力確認を行う。
  //  * @function
  //  * @memberOf SimpleCrudDetailForm
  //  */
  doOrder = () => {
    $(`#form-${this.#functionId}`).submit();
  };
}

new OrderInputPage('order-input');
