import LoginForm from './forms/login/login.form';

/**
 * ログインページクラス
 */
class LoginPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.form = new LoginForm(functionId);
  }
}
new LoginPage('login');
