import BaseForm from '@js/app/page/forms/base.form';

/**
 * 会員情報更新 顧客情報取得ページクラス
 */
class MembersEdit extends BaseForm {
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
     * パスワードテキストボックスのフォーカス外れイベント
     */
    $('#password').on('blur', (e) => {
      if (e.target.value === '') {
        //必須解除
        $('#dt_password_confirm').attr({
          class: ''
        });
        $('#password_confirm').attr({
          'data-parsley-validate': 'novalidate',
          'data-parsley-required': 'false',
          'data-parsley-equalto': ''
        });

        // バリデーションの状態をリセット
        this.resetValidate('#password_confirm');
      } else {
        //必須設定
        $('#dt_password_confirm').attr({
          class: 'required'
        });
        $('#password_confirm').attr({
          'data-parsley-validate': 'validate',
          'data-parsley-required': 'ture',
          'data-parsley-equalto': '#password'
        });

        // バリデーションの状態をリセット
        this.resetValidate('#password_confirm');
      }
      super.parsleySetup();
    });

    /**
     * 変更するボタンクリック
     */
    $(`#members-edit-btn-change`).on('click', (e) => {
      //入力チェック
      if (super.hasError()) {
        return false;
      } else {
        var result = window.confirm('更新します。よろしいですか？');
        if (result) {
          const form = document.getElementById('form-members-edit');
          form.submit();
        } else {
          return false;
        }
      }
    });

    /**
     * マイページトップに戻るボタンクリック
     */
    $(`#members-edit-btn-retmypage.button.right.link`).on('click', (e) => {
      window.location.href = '/members/home';
    });
  }
}
new MembersEdit('members-edit');
