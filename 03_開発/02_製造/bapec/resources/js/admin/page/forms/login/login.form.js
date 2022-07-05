import BaseForm from '@js/app/page/forms/base.form';

/**
 * ログイン画面クラス
 */
class LoginForm extends BaseForm {
  /**
   * 画面処理ID
   * @memberOf LoginForm
   */
  #functionId;
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    // エラー通知をツールチップに変更
    fw.config.page.validation['error-notify'] = 'tooltip';
    fw.config.page.validation['notify-wrapper'] = false;

    super(`#form-${functionId}`);
    /**
     * ログインボタン押下
     */
    $('#btn-login').on('click', (e) => {
      super.validate();
      if (this.hasError()) {
        return;
      }
      $(this.formSelector).submit();
    });

    // 入力項目にてキー押下時
    $('#login-id, #password').on('keypress', (e) => {
      // Enter押下時、ログインボタンのクリックイベントを起こす
      if (e.keyCode == 13) {
        $('#btn-login').trigger('click');
      }
    });
  }
}
export default LoginForm;
