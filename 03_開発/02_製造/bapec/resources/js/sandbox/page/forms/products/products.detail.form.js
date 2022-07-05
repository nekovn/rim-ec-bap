import {SimpleCrudDetailForm} from '@js/app/page/forms/simple.crud.detail.form';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';

/**
 * 顧客登録・編集画面クラス
 */
class ProductsDetailForm extends SimpleCrudDetailForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      store: '/api/admin/products/store',
      edit: '/api/admin/products/:id/edit',
      update: '/api/admin/products/:id',
      delete: '/api/admin/products/:id',
      fileupload: '/api/admin/products/upload'
    };

    super(functionId, requestUrls);

    /**
     * アップロードボタン押下
     */
    $(`#${functionId}-btn-upload`).on('click', (e) => {
      //入力チェック
      let param = {};
      let file = {
        attaches: {
          name: 'imageFile',
          file: document.getElementById('product').files[0]
        }
      };
      Object.assign(param, file);
      const xhrParam = new XhrParam('/api/admin/products/upload/image', param);
      XHRCrudMethod.upload(xhrParam, () => {
        return;
      });
      return xhrParam;
    });

    /**
     * 画像変更イベント
     */
    $(`#upload_image`).on('change', () => this.imagePreview());
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
    this.changeRequired('#email', true);
    $('#email').attr('readonly', false);
    this.changeRequired('#password', true);
  }
  /**
   * 登録時の後処理を行う。
   * @param {object} レスポンス
   * @return {object} レスポンス
   */
  afterStore(res) {
    this.changeRequired('#email', false);
    $('#email').attr('readonly', true);
    this.changeRequired('#password', false);
    $('#password').val('');
    $('#password_confirm').val('');
    return res;
  }

  /**
   * 更新時、初期表示前処理
   * @param {object} param 更新初期表示パラメーター
   */
  preEditInitialize(param) {
    super.preEditInitialize(param);

    $('.custom-file-label').text('');
    $('#image_preview').attr('src', '').removeClass('exist');

    $('#name').prop('disabled', true);
    $('#name_kana').prop('disabled', true);
    $('#brand_name').prop('disabled', true);
    $('#disp_category').prop('disabled', true);
    $('#case_qty').prop('disabled', true);
    $('#class_div').prop('disabled', true);
    $('#un_edited_dsp').prop('disabled', true);
    $('#edited_at_dsp').prop('disabled', true);
    $('#import_at').prop('disabled', true);
    $('#qty_disp').prop('disabled', true);
    $('#order_lot_disp').prop('disabled', true);
    $('#code').attr('readonly', true);
  }

  /**
   * 更新時、初期表示後処理
   * @param {object} res レスポンス
   */
  afterEditInitialize(res) {
    super.afterEditInitialize(res);
    this.itemSet();
  }

  /**
   * 更新後、初期表示後処理
   * @param {object} res レスポンス
   */
  afterUpdate(res) {
    super.afterUpdate(res);

    // 編集状態再取得
    let unedited = '';
    unedited =
      res.unedited != fw.define.UneditedDefine.UNEDITED ? '通常' : '未編集';
    $('#un_edited_dsp').val(unedited);

    // 更新日時再取得
    let date = res.edited_at;
    $('#edited_at_dsp').val(date);

    // カテゴリ名再設定(更新の場合、別項目で値が設定される為)
    res.class1_name = res.categorie.class1_name;
    res.class2_name = res.categorie.class2_name;
  }

  /**
   * 項目値をセット
   */
  itemSet() {
    // 入り数
    var qty_disp = '';
    if ('' != $('#case_qty').val()) {
      qty_disp += '(ケース) ' + $('#case_qty').val() + '　　';
    }
    if ('' != $('#inbox_qty').val()) {
      qty_disp += '(内箱) ' + $('#inbox_qty').val() + '　　';
    }
    if ('' != $('#fraction_qty').val()) {
      qty_disp += '(バラ) ' + $('#fraction_qty').val();
    }
    $('#qty_disp').val(qty_disp);

    // 受注ロット
    if (fw.define.OrderLotTypeDefine.CASE_QTY == $('#order_lot_type').val()) {
      $('#order_lot_disp').val('(ケース) ' + $('#order_lot').val());
    } else if (
      fw.define.OrderLotTypeDefine.INBOX_QTY == $('#order_lot_type').val()
    ) {
      $('#order_lot_disp').val('(内箱) ' + $('#order_lot').val());
    } else if (
      fw.define.OrderLotTypeDefine.FRACTION_QTY == $('#order_lot_type').val()
    ) {
      $('#order_lot_disp').val('(バラ) ' + $('#order_lot').val());
    } else {
      $('#order_lot_disp').val('');
    }

    // 編集状態
    let unedited = '';
    if ('' != $('#unedited').val()) {
      unedited = $('#unedited').val() == '0' ? '通常' : '未編集';
    }
    $('#un_edited_dsp').val(unedited);

    // 更新日時
    if ('' != $('#edited_at').val()) {
      let date = $('#edited_at');
      $('#edited_at_dsp').val(date[0]['value']);
    }

    // 画像
    if ($('#image').val() != '') {
      let $path = $('#image').val().split('/');
      let $file_name = $path.pop();
      const preview = document.getElementById('image_preview');
      preview.src = `/storage/images/products/${$file_name}`;
      preview.classList.add('exist');
    }
    // カテゴリ
    if ($('#category_code').val() != '') {
      var class1 = window.class1;
      var class2 = window.class2;
      var class1_code = $('#category_code').val().substr(0, 2);
      var class2_code = $('#category_code').val();

      for (let i = 0; i < class2[class1_code].length; i++) {
        if (class2_code == class1_code + class2[class1_code][i]['value']) {
          $('#disp_category').val(
            class1[class1_code] + ' > ' + class2[class1_code][i]['text']
          );
        }
      }
    }
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
    }

    if (file.name) {
      $('#image').val(`/storage/images/products/${file.name}`);
    }
  }
}

export default ProductsDetailForm;
