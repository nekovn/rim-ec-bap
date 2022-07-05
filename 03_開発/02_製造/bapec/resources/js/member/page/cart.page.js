import BaseForm from '@js/app/page/forms/base.form';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import {Header} from '@js/member/page/header';

/**
 * カートページクラス
 */
class CartPage extends BaseForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    super(`#form-${functionId}`);
    this.functionId = functionId;

    this.requestUrls = {
      delete: '/api/member/cart',
      update: '/api/member/cart'
    };

    this.setUpFormObserver();

    /**
     * 数量変更時
     */
    $(`.${this.functionId}-item-qty`).on('change', (e) => {
      let parameter = this.createRowIdParameter(e);
      parameter.qty = e.currentTarget.value;

      const xhrParam = new XhrParam(this.requestUrls.update, parameter);
      const successHandler = (res) => {
        this.resetFormObserver();
        this.renderTable(res);
      };
      XHRCrudMethod.update(xhrParam, successHandler, false);
    });
    /**
     * 削除ボタン押下
     */
    $(`.${this.functionId}-btn-item-delete`).on('click', (e) => {
      const xhrParam = new XhrParam(
        this.requestUrls.delete,
        this.createRowIdParameter(e)
      );
      const successHandler = (res) => {
        this.resetFormObserver();
        this.renderTable(res);
        // ヘッダー部カート件数更新
        Header.doUpdateHeaderCount();
      };
      XHRCrudMethod.delete(xhrParam, successHandler, false);
    });

    /**
     * 購入手続きに進むボタン
     */
    $(`#${this.functionId}-btn-next`).on('click', (e) => {
      //注文入力画面へ遷移する。
      $(`#form-${this.functionId}`).submit();
    });

    /**
     * お買い物を続けるボタン
     */
    $(`#${this.functionId}-btn-back`).on('click', (e) => {
      //注文入力画面へ遷移する。
      window.location.href = '/';
    });
  }
  /**
   * リクエストパラメーターを作成する。
   * @param {event} e table tr内のイベント
   * @return {object} rowidリクエストパラメータ(json)
   */
  createRowIdParameter(e) {
    const target = e.currentTarget;
    const tr = target.closest('.item-row');
    const rowId = tr.dataset.rowid;

    return {
      row_id: rowId
    };
  }
  /**
   * データの再表示
   * @param {json} data
   */
  renderTable(data) {
    if (Object.keys(data.items) <= 0) {
      //データが空になった
      //   document.getElementById(`${this.functionId}-item-table"`).remove();
      $('.data-on').remove();
      $('.data-empty').removeClass('d-none');

      return false;
    }

    let elems = document.querySelectorAll(`.item-row`);
    for (let i = 0; i < elems.length; i++) {
      const elemRowId = elems[i].dataset.rowid;
      if (elemRowId in data.items) {
        const rowData = data.items[elemRowId];
        //行bind
        super.bindValue(rowData, '[data-rowid="' + elemRowId + '"]');
        //小計
        document.getElementsByName('total')[0].innerHTML = data.total;
      } else {
        elems[i].remove();
      }
    }
  }
  //   createTableHtml = (data) => {
  //     if (Object.keys(data.items).length > 0) {
  //       let html = `<table cellpadding="0" cellspacing="0" class="table_cart" id="${this.functionId}-item-table">`;
  //       html += ` <tr>`;
  //       html += `  <th>商品画像</th>`;
  //       html += `  <th>商品名</th>`;
  //       html += `  <th>規格</th>`;
  //       html += `  <th>税込単価</th>`;
  //       html += `  <th>数量</th>`;
  //       html += `  <th>小計</th>`;
  //       html += `  <th></th>`;
  //       html += ` </tr>`;
  //       html += `   <tbody>`;
  //       for (let item of data['items']) {
  //         html += `     <tr class="item-row" data-rowid="${item.rowId}">`;
  //         html += `      <td><img src="{{ asset('storage/images/goods/3/main.jpg')}}" alt=""></td>`;
  //         html += `      <td><span class="sp">商品名：</span><span name="name">{{ $item-> name}}</span></td>`;
  //         html += `      <td><span class="sp">規格：</span><span name="volume">{{ $item-> volume}}</span></td>`;
  //         html += `      <td><span class="sp">税込単価：</span><span name="salePriceTaxIncluded">{{ $item-> salePriceTaxIncluded}}</span></td>`;
  //         html += `      <td><span class="sp">数量：</span>`;
  //         html += `      <select class="quantity item-qty" >`;
  //         for (let idx = 1; idx <= 100; idx++) {
  //           html += `         <option value="${idx}" ${
  //             item.qty == idx ? 'selected' : ''
  //           }>${idx}</option>`;
  //         }
  //         html += `      </select>`;
  //       }
  //       html += `    </td>`;
  //       html += `      <td><span class="sp">小計：￥</span>{{ $item-> subtotalTaxIncluded}}</td>`;
  //       html += `     <td><button type="button" class="button {{$functionId}}-btn-item-delete" >削除</button></td>`;
  //       html += `      </tr>`;
  //       html += `   </tbody>`;
  //       html += `   </table>`;

  //       html += `  <p class="total">小計：￥<span name="total">{{ $cartitems['total']}}</p>`;
  //       html += `  <p class="txt_guide">送料、手数料等については注文確認画面にて表示される旨の案内を表示</p>`;

  //       $('.data-on').removeClass('d-none');

  //       document.getElementById(`${this.functionId}-list`).innerHTML = html;
  //     } else {
  //       $('.data-on').addClass('d-none');
  //     }
  //   };
}
new CartPage('cart');
