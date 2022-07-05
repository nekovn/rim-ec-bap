import BaseForm from '@js/app/page/forms/base.form';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import {Header} from '@js/member/page/header';

/**
 * 商品詳細 カート登録ページクラス
 */
class GoodsDetailPage extends BaseForm {
  /**
   * 画面処理ID
   */
  #functionId;

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    super(`${functionId}`);
    this.#functionId = functionId;

    // ページの先頭へ戻るボタンにクラス追加
    $('#pageTop').addClass('cart_top');

    /**
     * カートに追加ボタンクリック
     */
    $(`#${this.#functionId}-btn-cartin.button.link`).on('click', (e) => {
      const goods_id = document.getElementById('goods_id').value;
      const qty = document.getElementById('qty').value;
      const param = {
        goods_id: goods_id,
        qty: qty
      };

      // カート追加API呼び出し
      const xhrParam = new XhrParam('/api/member/cart/add', param);
      const successHandler = (res) => {
        // ダイアログ表示
        // $('#cartAddDialog').modal('show');
        $.featherlight($('#cartAddDialog'), {
          otherClose: '#cartAddDialogClose'
        });

        // ヘッダー部カート件数更新
        Header.doUpdateHeaderCount();
      };
      XHRCrudMethod.store(xhrParam, successHandler, false);
    });
  }
}
new GoodsDetailPage('goods-detail');
