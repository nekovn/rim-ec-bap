//import {ModalMessage} from '@js/service/notifications';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import Number from '@js/service/number';

/**
 * ポイント変更ページクラス
 */
export default class OrderChangePoint {
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

    // 画面ロード後
    $(window).on('load', function () {
      // 支払い方法のリフレッシュ
      refreshPaymentMethod();
    });

    /**
     * ポイントを入力
     */
    $(`#${this.#functionId}-point`).on('change', (e) => {
      //ポイント入力チェック
      const point = e.target.value;

      ///保有ポイント素子取得
      const elmPoint = $(`#${this.#functionId}-owned-point`);

      ///合計（税込）素子取得
      // const elmTotal = $(`#${this.#functionId}-totalTaxIncluded`);

      //保有ポイント
      const ownedPoint = elmPoint.data('point');

      ////元の保有ポイント
      // const originPoint = elmPoint.data('origin');

      ////元の合計（税込）
      // const originTotal = elmTotal.data('total');

      if (point) {
        //正の整数以外をチェック
        const checkNumber = Number.isNumber(point);
        ///ポイントインプット素子取得
        const elmInput = document.getElementById(`${this.#functionId}-point`);
        //ポイント利用
        const elmButton = document.getElementById(
          `${this.#functionId}-recalculation`
        );

        if (!checkNumber) {
          //エラーメッセージをトーストを表示する。
          //ModalMessage.showDanger(fw.i18n.messages['E.input.integer']);
          alert(fw.i18n.messages['E.input.integer']);
          //ゼロにポイントをセット
          elmInput.value = 0;
          //ゼロにButtonのバリューをセット
          elmButton.value = 0;
          //元の保有ポイントをセット
          // elmPoint
          //   .attr('data-point', originPoint)
          //   .html(`（保有ポイント：${originPoint}pt）`);
          // //元の合計（税込）をセット
          // elmTotal.attr('data-total', originTotal).html(`￥${originTotal}`);

          return;
        }
        //保有ポイント数を超えるチェック

        if (parseInt(point) > parseInt(ownedPoint)) {
          //エラーメッセージをトーストを表示する。
          //ModalMessage.showDanger(fw.i18n.messages['E.insufficient.points']);
          alert(fw.i18n.messages['E.insufficient.points']);
          //ゼロにポイントをセット
          elmInput.value = 0;
          //ゼロにButtonのバリューをセット
          elmButton.value = 0;
          //元の保有ポイントをセット
          // elmPoint
          //   .attr('data-point', originPoint)
          //   .html(`（保有ポイント：${originPoint}pt）`);
          // //元の合計（税込）をセット
          // elmTotal.attr('data-total', originTotal).html(`￥${originTotal}`);

          return;
        }
        //エラーがないと、Inputに入力したポイントをセット
        elmInput.value = point;
        //エラーがないと、Buttonに入力したポイントをセット
        elmButton.value = point;
      }
    });

    //料金再計算ボタンをクリック
    $(`#${this.#functionId}-recalculation`).on('click', (e) => {
      //決済コードを取得
      const point = e.target.value;
      if (point) {
        // 決済方法変更API呼び出し
        const xhrParam = new XhrParam('/api/member/order/changePoint', {point});
        const successHandler = (res) => {
          if (res.error) {
            //エラーメッセージをトーストを表示する。
            //ModalMessage.showDanger(res.error);
            alert(res.error);
            return;
          }
          if (res.data) {
            //金額部をリフレッシュ
            refreshMoney(res.data);
            //保有ポイントをリフレッシュ
            refreshPoint(res.data);
            // 支払い方法のリフレッシュ
            refreshPaymentMethod();

            //更新完了トーストを表示する。
            // Toaster.showUpdateToaster();
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
      //合計（税込)表示を処理
      $(`#${this.#functionId}-totalTaxIncluded`).html(
        Number.formatNumberWithSimbol(res['totalTaxIncluded'])
      );
    };

    /**
     * 保有ポイントを行う。
     * @function
     * @param {object} res APIのレスポンスを受け
     */
    const refreshPoint = (res) => {
      //保有ポイント表示を処理
      $(`#${this.#functionId}-owned-point`)
        .attr('data-point', res['ownedPoint'])
        .html(`保有ポイント：${res['ownedPoint']}pt`);
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

    $(`#${this.#functionId}-point`).trigger('change');
  }
}
