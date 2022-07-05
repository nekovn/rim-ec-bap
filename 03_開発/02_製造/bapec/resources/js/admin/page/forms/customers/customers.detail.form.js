import {SimpleCrudDetailForm} from '@js/app/page/forms/simple.crud.detail.form';

/**
 * 顧客登録・編集画面クラス
 */
class CustomersDetailForm extends SimpleCrudDetailForm {
  /**
   * 画面タイプ
   * (new=新規画面 edit=編集画面)
   */
  #screenType;

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      store: '/api/admin/customers/store',
      edit: '/api/admin/customers/:id/edit',
      update: '/api/admin/customers/:id',
      delete: '/api/admin/customers/:id'
    };

    super(functionId, requestUrls);

    /**
     * パスワードテキストボックスのフォーカス外れイベント
     */
    $('#password').on('blur', (e) => {
      // 編集画面が対象
      if (this.#screenType === 'edit') {
        if (e.target.value === '') {
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
    this.changeRequired('#password', true);
    $('#bcrews_customer_id_status').text('未連携');
    $('#bcrews_customer_id').text('');
    $('#bcrews_customer_id_wrapper').hide();
    $('#bcrew_customer_id_status').text('未連携');
    $('#bcrew_customer_id').text('');
    $('#bcrew_customer_id_wrapper').hide();
    $('#point').val('');
    $('#point').prop('disabled', true);
    $('#customer_rank').val('一般');
    $('#customer_rank').prop('disabled', true);
    this.#screenType = 'new';
  }

  /**
   * 登録時の後処理を行う。
   * @param {object} レスポンス
   * @return {object} レスポンス
   */
  afterStore(res) {
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
    this.changeRequired('#password', false);
    this.changeRequired('#password_confirm', false);
    this.#screenType = 'edit';
  }

  /**
   * 更新時、初期表示の後処理を行う。
   * @param {object} res レスポンス
   */
  afterEditInitialize(res) {
    if (res.data.bcrews_customer_id === null) {
      $('#bcrews_customer_id_status').text('未連携');
      $('#bcrews_customer_id').text('');
      $('#bcrews_customer_id_wrapper').hide();
    } else {
      $('#bcrews_customer_id_status').text('連携済み');
      $('#bcrews_customer_id').text(res.data.bcrews_customer_id);
      $('#bcrews_customer_id_wrapper').show();
    }

    if (res.data.bcrew_customer_id === null) {
      $('#bcrew_customer_id_status').text('未連携');
      $('#bcrew_customer_id').text('');
      $('#bcrew_customer_id_wrapper').hide();
    } else {
      $('#bcrew_customer_id_status').text('連携済み');
      $('#bcrew_customer_id').text(res.data.bcrew_customer_id);
      $('#bcrew_customer_id_wrapper').show();
    }

    $('#point').prop('disabled', true);

    $('#customer_rank').val(res.data.rank_name);
    $('#customer_rank').prop('disabled', true);
  }
}
export default CustomersDetailForm;
