import BaseForm from '@js/app/page/forms/base.form';
import Messages from '@js/app/messages';

/**
 * 顧客登録ページクラス
 */
class MemberRegisterPage extends BaseForm {
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

    /**
     * 都道府県選択リスト変更
     */
    $(`#prefcode`).on('change', (e) => {
      // 郵便番号、住所を空にする
      document.getElementById('zip').value = '';
      document.getElementById('addr_1').value = '';
      //document.getElementById('addr_2').value = '';
      //document.getElementById('addr_3').value = '';
    });

    /**
     * 登録ボタン押下
     */
    $(`#${this.#functionId}-btn-store`).on('click', (e) => {
      //入力チェック
      if (super.hasError()) {
        return false;
      }

      if ($('#agree').prop('checked') == false) {
        alert(Messages.getMessage('E.terms.agree'));
        return false;
      }

      var result = window.confirm(
        Messages.getMessage('C.customer.registration')
      );
      if (result) {
        const form = document.getElementById('form-member-register');
        form.submit();
      } else {
        return false;
      }
    });
  }

  /**
   * 登録処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   */
  /*
  doRegist = () => {
    $(`#form-${this.#functionId}`).submit();
  };
  */
}
new MemberRegisterPage('member-register');
