import {SimpleCrudDetailForm} from '@js/app/page/forms/simple.crud.detail.form';

/**
 * ユーザー登録・編集画面クラス
 */
class UsersDetailForm extends SimpleCrudDetailForm {
  /**
   * 画面タイプ
   * (new=新規画面 edit=編集画面)
   * @memberof MembersDetailForm
   */
  #screenType;

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      store: '/api/admin/users/store',
      edit: '/api/admin/users/:id/edit',
      update: '/api/admin/users/:id',
      delete: '/api/admin/users/:id'
    };

    super(functionId, requestUrls);

    /**
     * パスワードテキストボックスのフォーカス外れイベント
     */
    $('#password').on('blur', (e) => {
      // 編集画面が対象
      if (this.#screenType === 'edit') {
        if (e.target.value == '') {
          // 必須解除
          this.changeRequired('#password_confirm', false);
          // バリデーションの状態をリセット
          this.resetValidate('#password_confirm');
        } else {
          // 必須設定
          this.changeRequired('#password_confirm', true);
        }
        super.parsleySetup();
      }
    });
  }
  /**
   * 新規登録時、初期表示前処理
   */
  preNewInitialize() {
    super.preNewInitialize();
    this.changeRequired('#code', true);
    $('#code').attr('readonly', false);
    this.changeRequired('#email', false);
    $('#email').attr('readonly', false);
    this.changeRequired('#password', true);
    this.#screenType = 'new';
  }
  /**
   * 登録時の後処理を行う。
   * @param {object} レスポンス
   * @return {object} レスポンス
   */
  afterStore(res) {
    this.changeRequired('#code', false);
    $('#code').attr('readonly', true);
    this.changeRequired('#email', false);
    $('#email').attr('readonly', true);
    this.changeRequired('#password', false);
    this.changeRequired('#password_confirm', false);
    $('#password').val('');
    $('#password_confirm').val('');
    this.#screenType = 'edit';
    return res;
  }
  /**
   * 更新時、初期表示前処理
   * @param {object} param 更新初期表示パラメーター
   */
  preEditInitialize(param) {
    super.preEditInitialize(param);
    this.changeRequired('#code', false);
    $('#code').attr('readonly', true);
    this.changeRequired('#email', false);
    $('#email').attr('readonly', false);
    this.changeRequired('#password', false);
    this.changeRequired('#password_confirm', false);
    this.#screenType = 'edit';
  }
}
export default UsersDetailForm;
