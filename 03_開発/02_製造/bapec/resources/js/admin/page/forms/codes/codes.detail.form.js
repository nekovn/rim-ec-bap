import {SimpleCrudDetailForm} from '@js/app/page/forms/simple.crud.detail.form';

class CodesDetailForm extends SimpleCrudDetailForm {
  #updateSelectCode;

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      store: '/api/admin/codes/store',
      edit: '/api/admin/codes/:id/edit',
      update: '/api/admin/codes/:id',
      delete: '/api/admin/codes/:id'
    };

    super(functionId, requestUrls);
  }
  /**
   * 新規登録時、初期表示前処理
   */
  preNewInitialize() {
    super.preNewInitialize();
    // 入力・セレクト要素のコードを初期化し、セレクトに値設定
    $('#input-code').attr('readonly', false);
    $('#code').prop('disabled', false).val($('#search-select-code').val());
  }
  /**
   * 更新時、初期表示後処理
   * @param {object} res レスポンス
   */
  afterEditInitialize(res) {
    super.afterEditInitialize(res);

    // クラス変数にコードを設定し、入力・セレクト要素のコードをdisabledにする
    this.#updateSelectCode = res.data.code;
    $('#input-code').attr('readonly', true);
    $('#code').prop('disabled', true);
  }
  /**
   * 登録後処理
   * @param {object} レスポンス
   */
  afterStore(res) {
    super.afterStore(res);
    // 新規登録後、再検索を行うので登録したコード値のコードを、検索条件のコードセレクトボックスに設定する
    $('#search-select-code').val(res.code);
    // クラス変数にコードを設定し、入力・セレクト要素のコードをdisabledにする
    this.#updateSelectCode = res.code;
    $('#input-code').attr('readonly', true);
    $('#code').prop('disabled', true);
    return res;
  }
  /**
   * フォームをリセットする。
   */
  resetForm() {
    super.resetForm();
    // フォームリセット後に、コード入力欄のchangeイベントを発生させる事でコードのセレクトボックスをリセットする
    $('#input-code').trigger('change');
    // changeイベントを起こしたので、フォームの監視をリセットする
    super.resetFormObserver();
  }
  /**
   * カスタム属性追加用
   */
  createCustomUpdateParameter() {
    return {code: this.#updateSelectCode};
  }
}
export default CodesDetailForm;
