import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
//import {ModalMessage} from '@js/service/notifications';
import Number from '@js/service/number';

/**
 * 決済方法変更ページクラス
 */
export default class OrderChangePayment {
  /**
   * 画面処理ID
   */
  #functionId;

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.#functionId = functionId;

    /**
     * お支払い方法変更
     */
    $(`#${this.#functionId} input[name="payment"]`).on('change', (e) => {
      //決済コードを取得
      const payment_id = e.target.value;
      if (payment_id) {
        // 決済方法変更API呼び出し
        const xhrParam = new XhrParam('/api/member/order/changePayment', {
          payment_id
        });
        const successHandler = (res) => {
          console.log(res);
          if (res.error) {
            //エラーメッセージをトーストを表示する。
            //ModalMessage.showDanger(res.error);
            alert(res.error);
          }
          if (res.data && res.error.length == 0) {
            //金額部をリフレッシュ
            refreshMoney(res.data);
            // 支払い方法のリフレッシュ
            refreshPaymentMethod();
          }
        };
        XHRCrudMethod.store(xhrParam, successHandler, false);
      }
    });

    /**
     * 金額部リフレッシュを行う。
     * @function
     * @param {object} res APIのレスポンスを受け
     */
    const refreshMoney = (res) => {
      // 代引手数料のID
      const cash = $(`#${this.#functionId}-show-cash`);

      // 決済手数料表示制御
      if (
        res['paymentMethod'] == fw.define.PaymentMethodDefine.CASH_ON_DELIVERY
      ) {
        //代引き金額表示を処理
        cash.css('display', 'block');
        //決済手数料:税込み表示を処理
        $(`#${this.#functionId}-payment-cash`).html(
          Number.formatNumberWithSimbol(res['paymentFee'])
        );
      } else {
        cash.css('display', 'none');
      }

      //送料表示を処理
      $(`#${this.#functionId}-postage`).html(
        Number.formatNumberWithSimbol(res['postage'])
      );

      //合計（税込)表示を処理
      $(`#${this.#functionId}-totalTaxIncluded`).html(
        Number.formatNumberWithSimbol(res['totalTaxIncluded'])
      );
    };
    /**
     * 支払い方法のリフレッシュを行う。
     * @function
     */
    const refreshPaymentMethod = () => {
      // 選択している支払い方法の表示名称を取得
      const selectPaymentName = $('[name=payment]:checked')
        .next('label')
        .text();

      if ('￥0' === $('dd[id$="totalTaxIncluded"]').html()) {
        // 合計（税込)が０円の場合

        // 請求無し以外を非表示
        $('.charge').hide();
        // 請求無しを表示
        $('.no-charge').show();

        if (selectPaymentName.indexOf('請求無し') === -1) {
          // 請求無し以外を選択している場合は、請求無しを選択状態にする
          $(
            `[data-payment-method="${fw.define.PaymentMethodDefine.NO_CHARGE}"]`
          ).prop('checked', true);
          $(
            `[data-payment-method="${fw.define.PaymentMethodDefine.NO_CHARGE}"]`
          ).trigger('change');
        }
      } else {
        // 合計（税込)が０円でない場合

        // 請求無し以外を表示
        $('.charge').show();
        // 請求無しを非表示
        $('.no-charge').hide();

        // 請求無しが選択状態であればラジオボタンの選択解除
        if (selectPaymentName.indexOf('請求無し') !== -1) {
          // 請求無しを選択している場合は、選択状態を解除する
          $(
            `[data-payment-method="${fw.define.PaymentMethodDefine.CREDIT}"]`
          ).prop('checked', true);
          $(
            `[data-payment-method="${fw.define.PaymentMethodDefine.CREDIT}"]`
          ).trigger('change');
        }
      }
    };
  }
}
