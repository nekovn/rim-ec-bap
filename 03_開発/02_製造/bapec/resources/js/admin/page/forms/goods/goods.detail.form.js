import {SimpleCrudDetailForm} from '@js/app/page/forms/simple.crud.detail.form';
import Wysiwyg from '@js/service/wysiwyg';

/**
 * 商品登録・編集画面クラス
 */
class GoodsDetailForm extends SimpleCrudDetailForm {
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
      store: '/api/admin/goods/store',
      edit: '/api/admin/goods/:id/edit',
      update: '/api/admin/goods/:id',
      delete: '/api/admin/goods/:id'
    };

    super(functionId, requestUrls);

    /**
     * 画像変更イベント
     */
    $(`#upload_image`).on('change', () => this.imagePreview());

    /**
     * 商品コードフォーカスアウトイベント
     */
    $(`#code`).on('blur', (e) => {
      const code = $(e.target).val();
      $('#jan_code').val(code);
    });

    //商品コードは別途parsley設定

    const codeParsley = $('#code').parsley({
      // trigger: 'blur'
    });
    codeParsley.on('field:validated', (e) => {
      //remote validateはurlをキャッシュに持つためクリア
      window.Parsley._remoteCache = {};
    });
    /**
     * 入力された商品コード(value)が、
     * すでに存在する商品コード(window.codes)に含まれない場合 true
     */
    // window.parsley.addValidator('duplicatecode', {
    //   validateString: function (value, excludeCode, parsleyInstance) {
    //     parsleyInstance.reset();

    //     if (value.toString() === excludeCode.toString()) {
    //       return true;
    //     }

    //     return $.ajax(
    //       '/api/admin/goods/codes' +
    //         '?checkCode=' +
    //         value +
    //         '&excludeCode=' +
    //         excludeCode
    //     );
    //   },
    //   messages: {
    //     ja: '入力された商品コードは既に存在します'
    //   },
    //   priority: 32
    // });
  }

  /**
   * 画像のプレビュー表示
   */
  imagePreview() {
    const preview = document.getElementById('image_preview');
    const file = document.getElementById('upload_image').files[0];
    const reader = new FileReader();

    reader.addEventListener(
      'load',
      function () {
        // 画像ファイルを base64 文字列に変換します
        preview.src = reader.result;
        preview.classList.add('exist');
      },
      false
    );

    if (file) {
      reader.readAsDataURL(file);
      super.resetValidate('#image');
    }

    if (file.name) {
      $('#image').val(`${file.name}`);
    }
  }

  /**
   * カスタム属性追加用
   */
  createCustomParameter() {
    return {
      filedata: {
        name: 'upload_image',
        file: document.getElementById('upload_image').files[0]
      }
    };
  }

  /**
   * 新規登録時、初期表示前処理
   */
  preNewInitialize() {
    super.preNewInitialize();

    $('.custom-file-label').text('');
    $('#image_preview').attr('src', '').removeClass('exist');
    $('#image').val('');

    this.changeRequired('#code', true);
    $('#code').attr('readonly', false);
    document.getElementById('code').dataset.parsleyRemote =
      '/api/admin/goods/codes/{value}';
    // $('#code').attr('data-parsley-duplicatecode', '');

    // WYSIWYG
    $('#description').val('');
    Wysiwyg.setup('#description');
  }

  /**
   * 更新時、初期表示前処理
   * @param {object} param 更新初期表示パラメーター
   */
  preEditInitialize(param) {
    super.preEditInitialize(param);

    $('.custom-file-label').text('');
    $('#image_preview').attr('src', '').removeClass('exist');

    this.changeRequired('#code', false);
    $('#code').attr('readonly', true);

    delete document.getElementById('code').dataset.parsleyRemote;

    // 'data-parsley-remote'=> url('/api/admin/goods/codes/{value}'),
    //   'data-parsley-remote-options'=> '{"async":false}',

    // document.getElementById('code').dataset.ParsleyRemote =
    //   '/api/admin/goods/codes/{value}/' + param.id;
    // $('#code')[0].dataset.
    //   'parsleyRemoteOptions',
    //   `'{"async":false,"data":{"id",${param.id}}}'`
    // );
  }

  /**
   * 更新時、初期表示後処理
   * @param {object} res レスポンス
   */
  afterEditInitialize(res) {
    super.afterEditInitialize(res);

    // 画像プレビュー表示
    let path = res.data.image_url;
    if (path !== undefined && path !== null && path !== '') {
      const preview = document.getElementById('image_preview');
      preview.src = path;
      preview.classList.add('exist');
    }

    $('#code').attr('data-parsley-duplicatecode', res.data.code);

    // WYSIWYG
    Wysiwyg.setup('#description');
  }
}
export default GoodsDetailForm;
