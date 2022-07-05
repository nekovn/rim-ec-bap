import AnimateService from '@js/service/animate';
import CodeSearchForm from './forms/codes/codes.search.form.js';
import CodeDetailForm from './forms/codes/codes.detail.form.js';

/**
 * コードマスタページクラス
 */
class CodesPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    // 検索部のコードリストに絞り込み処理を設定
    this.narrowDownList('#search-select-code', '#search-input-code');
    // 編集部のコードリストに絞り込み処理を設定
    this.narrowDownList('#code', '#input-code');
    this.searchForm = new CodeSearchForm(functionId);
    this.detailForm = new CodeDetailForm(functionId);
    AnimateService.showContent(`${functionId}-list-area`);
  }

  /**
   * セレクトボックスの絞り込み処理を設定する
   * @param {string} list 絞り込みを行うセレクトボックス
   * @param {string} input 絞り込むための入力欄
   */
  // TODO 処理を画面上の複数のセレクト要素に設定する際に、そのセレクト項目が異なる場合は、片方が上書きされてしまうので対応必要
  narrowDownList(list, input) {
    var selectItem = $(list).prop('innerHTML');

    // コードの入力欄に入力すると、その値に応じてセレクトボックスの候補が絞り込まれる
    $(input).on('keyup change forcusout', (e) => {
      let $children = $(list);
      let parentValue = e.currentTarget.value;
      if (parentValue === '') {
        $children.html(selectItem);
        return;
      }

      // 取得しているセレクト項目リストの中から、入力された文字で始まっているものを絞り込む
      $children
        .html(selectItem)
        .find('option')
        .each((index, element) => {
          let childrenValue = $(element).attr('value');
          // 値が定義されていない場合、その要素を削除して次へ
          // ※startsWithに渡すとエラーになるため事前処理 基本的にundefinedにはならない
          if (childrenValue == undefined) {
            $(element).remove();
            return true;
          }

          // 値が入力された文字で始まっていない場合、その要素を削除する
          if (!childrenValue.startsWith(parentValue)) {
            $(element).remove();
          }
        });

      // 先頭を選択状態にする
      $children.prop('selectedIndex', 0);
    });
  }
}
new CodesPage('codes');
