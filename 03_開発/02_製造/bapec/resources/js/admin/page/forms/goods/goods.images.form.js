import BaseForm from '@js/app/page/forms/base.form';
import {ModalMessage} from '@js/service/notifications';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import StringService from '@js/service/string';
/**
 * 商品画像画面クラス
 */
class GoodsImagesForm extends BaseForm {
  /**
   * 画面処理ID
   */
  #functionId;
  /**
   * 商品ID
   */
  #goods_id = 0;
  /**
   * 最大連番
   */
  #maxId = 0;
  /**
   * リクエストURLs
   * @memberOf SimpleCrudDetailForm
   */
  #requestUrls = {};
  /**
   * 更新時用キー情報
   */
  #updateKey = {
    id: '',
    ol_updated_at: ''
  };

  /**
   * コンストラクタ
   * @param functionId
   * @param goods_id
   */
  constructor(functionId) {
    super(`#form-${functionId}`);
    this.#functionId = functionId;
    this.setUpFormObserver();

    this.#goods_id = window.goods_id;
    this.#requestUrls = {
      find: `/api/admin/goods/:id/thumbnail/find`,
      entry: `/api/admin/goods/:id/thumbnail/entry`,
      back: `/admin/goods/thumbnail/back`
    };
    this.#updateKey = {
      id: this.#goods_id,
      ol_updated_at: ''
    };

    // 初期表示
    this.doFind();

    /**
     * 戻るボタン押下
     */
    $('#btn-back').on('click', (e) => {
      window.location.href = this.#requestUrls.back;
    });

    /**
     * 登録ボタン押下
     */
    $('#btn-store').on('click', (e) => {
      //入力チェック
      super.validate();
      if (this.hasError()) {
        return;
      }

      ModalMessage.showModifyConfirm(
        ModalMessage.MODIFY_MODE.INS,
        null,
        this.doRegister
      );
    });

    /**
     * 追加ボタン押下
     */
    $(document).on('click', '#btn-add', (e) => {
      // フォームの入力チェックの定義を削除する
      this.resetValidate();
      $(this.formSelector).parsley().destroy();

      this.addThumbnail();

      // フォームの入力チェックの定義を再適用する
      this.parsleySetup();
      this.changeFormObserver();
      this.refreshAddBtn();
      $('#thumbnail_list').trigger('sortupdate');
    });

    // サムネイル画像変更イベント
    $(document).on('change', '.upload_image', (e) => {
      this.imagePreview(e.currentTarget.dataset.id);
      this.changeFormObserver();
    });

    // サムネイル削除ボタン押下
    $(document).on('click', '.btn-delete', (e) => {
      ModalMessage.showModifyConfirm(ModalMessage.MODIFY_MODE.DEL, null, () => {
        $(`#thumbnail_${e.currentTarget.dataset.id}`).remove();
        this.changeFormObserver();
        $('#thumbnail_list').trigger('sortupdate');
      });
    });

    // 画面変更イベント
    super.addEventBeforeUnload();
  }

  /**
   * 取得処理を行う。
   */
  doFind() {
    const url = StringService.bindParameter(
      this.#requestUrls.find,
      this.#updateKey
    );
    const xhrParam = new XhrParam(url);
    const successHandler = (res) => {
      this.allRemoveThumbnail();
      this.allAddThumbnail(res.data);
    };
    XHRCrudMethod.get(xhrParam, successHandler);
  }

  /**
   * サムネイル 入力フィールド全追加
   */
  allAddThumbnail(rows) {
    if (rows === undefined || rows === null) {
      return;
    }

    // フォームの入力チェックの定義を削除する
    this.resetValidate();
    $(this.formSelector).parsley().destroy();

    rows.forEach((row) => this.addThumbnail(row));

    // フォームの入力チェックの定義を再適用する
    this.parsleySetup();

    this.refreshAddBtn();

    // ドラッグアンドドロップ設定
    $('#thumbnail_list').sortable({
      cursor: 'move',
      opacity: 0.7
    });
    $('#thumbnail_list').on('sortupdate', function () {
      const lows = $(this).sortable('toArray');
      for (let i = 0; i < lows.length; i++) {
        $(`#${lows[i]}`).find('.display_order').val(i);
        $(`#${lows[i]}`).find('.display_order_dsp').text(i);
      }
    });
  }

  /**
   * サムネイル 入力フィールド追加
   */
  addThumbnail(row) {
    let display_order = null;
    let image = '';
    if (row !== undefined) {
      display_order = row.display_order;
      image = row.image_url;
    }

    this.#maxId++;

    let dummy = $('<div>');
    let clone = $('#thumbnail_list [id^=thumbnail]:first-child').clone(true);
    clone.removeClass('d-none').addClass('thumbnail');
    dummy.append(clone);
    let html = dummy.html().replace(/:id/g, this.#maxId);
    var nodes = $.parseHTML(html);
    var elem = $(nodes[0]);

    // 保存済み画像の表示設定
    if (image !== undefined && image !== null && image !== '') {
      $('.image_preview', elem).attr('src', `${image}`).addClass('exist');
    }
    $('.image', elem).val(image).prop('required', true);
    $('.display_order', elem).val(display_order);
    $('.display_order_dsp', elem).text(display_order);

    elem.appendTo('#thumbnail_list');
  }

  /**
   * サムネイル 入力フィールド全削除
   */
  allRemoveThumbnail() {
    $('.thumbnail').remove();
    this.#maxId = 0;
  }

  /**
   * 追加ボタンのリフレッシュ
   */
  refreshAddBtn() {
    $('#btn-add').remove();
    const addbtn = $(
      '<button type="button" id="btn-add" class="btn btn-outline-dark m-2"><i class="fas fa-plus"></i>サムネイルを追加</button>'
    );
    addbtn.appendTo('#thumbnail_list');
  }

  /**
   * 画像のプレビュー表示
   */
  imagePreview(id) {
    const preview = document.getElementById(`image_preview_${id}`);
    const file = document.getElementById(`upload_image_${id}`).files[0];
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
      super.resetValidate(`#image_${id}`);

      if (file.name) {
        $(`#image_${id}`).val(`${file.name}`);
      }
    }
  }

  /**
   * 登録処理を行う。
   */
  doRegister = () => {
    const url = StringService.bindParameter(
      this.#requestUrls.entry,
      this.#updateKey
    );

    const xhrParam = new XhrParam(url, this.createParameter());

    const successHandler = (res) => {
      this.resetFormObserver();
      this.allRemoveThumbnail();
      this.allAddThumbnail(res.data);
    };

    XHRCrudMethod.uploads(xhrParam, successHandler);
  };

  /**
   * 登録時のリクエストパラメーターを作成する。
   */
  createParameter() {
    const param = this.formValueToJson();
    $(`#form-${this.#functionId} [data-html-only]`).each((index, element) => {
      delete param[element.id];
    });

    // ファイルのパラメータ設定
    for (let id = 1; id <= this.#maxId; id++) {
      let elementId = `upload_image_${id}`;

      if (document.getElementById(elementId) !== null) {
        const fileParameter = {};
        fileParameter[`filedata_${id}`] = {
          name: elementId,
          file: document.getElementById(elementId).files[0]
        };

        Object.assign(param, fileParameter);
      }
    }

    param['maxId'] = this.#maxId;

    return param;
  }
}

export default GoodsImagesForm;
