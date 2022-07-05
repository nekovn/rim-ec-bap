/**
 * Form共通定義
 *
 * @classdesc path: @js/base/forms/base.page.js
 */
class BaseForm {
  /**
   * フォームID
   * @memberOf BaseForm
   */
  #formSelector;
  /**
   * フォームオブザーバー（変更監視）
   * @memberOf BaseForm
   */
  #formObserver;
  /**
   * コンストラクタ
   *
   * @param {string} formSelector フォーム要素のセレクター
   *
   * <h5>提供機能</h5>
   * <div>
   *  <p>・validation parsley setup</p>
   *  <p>・戻るボタン押下時toast全て削除</p>
   * </div>
   */
  constructor(formSelector) {
    this.#formSelector = formSelector;
    this.#formObserver = false;
    this.parsleySetup();

    // input textのautocompleteを無効にする。
    $(formSelector).attr('autocomplete', 'off');

    // 戻るボタン押下時toast全て削除
    $('.btn-back').on('click', () => {
      $.toast().reset('all'); //toast remove
    });

    //ファイルを選択したらファイル名を表示（bootstrap custom-file-input）
    $(document).on('change', '.custom-file-input', function () {
      let files = $(this)[0].files;
      if (files.length) {
        $(this).next('.custom-file-label').html($(this)[0].files[0].name);
      } else {
        $(this).next('.custom-file-label').html('');
      }
    });
  }
  /**
   * 画面遷移時の確認メッセージを表示するするイベントを追加する
   * submitでも働くため、submitを使用する場合は
   * submitのonclickイベントで、window.onbeforeunload = nullを設定する必要がある
   */
  addEventBeforeUnload() {
    window.onbeforeunload = (e) => {
      if (this.isFormChanged()) {
        e.preventDefault();
        // IE以外のブラウザではセキュリティ上確認メッセージを指定できない（edge,firefox,chromeのシステムでは反映されない）が、
        //Chrome では returnValue を何かしら設定しないとダイアログが表示されない
        e.returnValue = fw.i18n.messages['C.changeclose.confirm']; //適当に設定。
      }
    };
  }
  /**
   * フォームの項目に値を設定する。
   * @param {object} param フォーム設定値
   */
  bindValue(param, selector = '') {
    Object.keys(param).forEach((key) => {
      const $element = $(`${selector} *[name=${key}]`);
      if ($element && $element.length) {
        if ($element.attr('type') === 'checkbox') {
          $element.prop('checked', $element.val() == param[key]);
        } else if ($element.attr('type') === 'radio') {
          $element.each((index, radio) => {
            $(radio).prop('checked', $(radio).val() == param[key]);
          });
        } else if ($element.is('input,textarea,select')) {
          $element.val(param[key]);
        } else {
          $element.html(param[key]);
        }
      }
    });
  }
  /**
   * formの入力内容をjsonに変換する。
   * @function
   * @memberOf BaseForm
   * @param {string} disposalPrefix プレフィックス文字列を削除する。 exclusionSelector:除外
   * @return {object} json文字列
   */
  formValueToJson(disposalPrefix = null, exclusionSelector = '') {
    const data = $(this.#formSelector).serializeArray();
    let exclu = null;
    if (exclusionSelector) {
      exclu = document.querySelectorAll(exclusionSelector);
    }
    const returnJson = {};
    for (let idx = 0; idx < data.length; idx++) {
      if (disposalPrefix && !_.startsWith(data[idx].name, disposalPrefix)) {
        continue;
      }
      if (exclu != null) {
        for (const excluElem of exclu) {
          if (excluElem.name == data[idx].name) {
            continue;
          }
        }
      }
      const key = disposalPrefix
        ? data[idx].name.substr(disposalPrefix.length)
        : data[idx].name;
      if (key in returnJson) {
        //既に存在する時は,区切りにする※配列は返せなかった
        returnJson[key] += ',' + data[idx].value;
      } else {
        returnJson[key] = data[idx].value;
      }
    }
    return returnJson;
  }
  /**
   * フォームに入力チェックの定義を適用する。
   * @function
   * @memberOf BaseForm
   */
  parsleySetup() {
    let pasleyObj = {
      // チェックタイミング設定
      trigger: fw.config.page.validation['validation-timing']
    };
    if (fw.config.page.validation['error-notify'] === 'tooltip') {
      //メッセージ表示場所設定
      pasleyObj.errorsContainer = (ParsleyField) => {
        return ParsleyField.$element.attr('title');
      };
      pasleyObj.errorsWrapper = fw.config.page.validation['notify-wrapper'];
    }
    $(this.#formSelector).parsley(
      pasleyObj
      // {
      // trigger: fw.config.page.validation['validation-timing'],
      // //メッセージ表示場所設定
      // errorsContainer: (ParsleyField) => {
      //   return ParsleyField.$element.attr('title');
      // },
      // errorsWrapper: fw.config.page.validation['notify-wrapper']
      // }
    );
  }
  /**
   * バリデーションを実行する。
   * @function
   * @memberOf BaseForm
   * @return {bool} バリデーション結果
   */
  validate() {
    const count = $(this.#formSelector).length;
    if (count == 1) {
      $(this.#formSelector)
        .parsley()
        .validate();
    } else {
      $(this.#formSelector).each(function () {
        $(this).parsley().validate();
      });
    }
  }
  /**
   * 指定した要素内に、エラー項目が存在するか判定する。
   * @function
   * @memberOf BaseForm
   * @return {boolean} エラー有無
   */
  hasError() {
    this.validate();
    return $(`${this.#formSelector} .parsley-error`).length > 0;
  }
  /**
   * フォーム内の要素にエラー状態の項目か判定する。
   * @function
   * @memberOf BaseForm
   * @param {string} elementId 要素を示すjQueryIDセレクター
   * @return {boolean} エラー状態
   */
  hasErrorElement(elementId) {
    return $(`${this.#formSelector} ${elementId}`).hasClass('parsley-error');
  }
  /**
   * フォーム内の指定要素のバリデーションの状態をリセットする。
   * @function
   * @memberOf BaseForm
   * @param {string} elementId 要素を示すjQueryセレクター
   * @desc DateServiceにも同様の機能あり
   */
  resetValidate(elementId = '') {
    const target = $(`${this.#formSelector} ${elementId}`);
    if (target.hasClass('tooltipstered')) {
      target.tooltipster('destroy');
    }
    if (typeof target.parsley().reset() == 'function') {
      target.parsley().reset();
    }
    target.removeAttr('title');
  }
  /**
   * 指定した要素内の全てのバリデーション状態をリセットする。
   * @function
   * @memberOf BaseForm
   */
  resetValidateAll() {
    const count = $(this.#formSelector).length;
    if (count == 1) {
      $(this.#formSelector)
        .parsley()
        .reset();
    } else {
      $(this.#formSelector).each(function () {
        $(this).parsley().reset();
      });
    }
    $(`${this.#formSelector} .is-invalid`).removeClass('is-invalid');

    const tooltipsters = $(`${this.#formSelector} .tooltipstered`);
    if (tooltipsters.length > 0) {
      tooltipsters.tooltipster('destroy');
    }
  }
  /**
   * フォームをリセットする。
   * @function
   * @memberOf BaseForm
   */
  resetForm() {
    const count = $(this.#formSelector).length;
    if (count === 0) {
      throw SyntaxError(`form id指定誤り(${this._formSelector})`);
    }
    if (count == 1) {
      $(this.#formSelector)[0].reset();
    } else {
      $(this.#formSelector).each((index, form) => form.reset());
    }

    this.resetValidateAll();
  }
  /**
   * フォームをクリアする。
   */
  clearForm() {
    const count = $(this.#formSelector).length;
    if (count === 0) {
      throw SyntaxError(`form id指定誤り(${this.#formSelector})`);
    }
    if (count == 1) {
      this.clearFormCore($(this.#formSelector)[0]);
    } else {
      $(this.#formSelector).each((index, form) => {
        this.clearFormCore(form);
      });
    }
    this.resetValidateAll();
  }
  /**
   * フォームクリア本体
   * @param {}} form
   */
  clearFormCore(form) {
    $(form)
      .find('input,textarea')
      .not(':button, :submit,:reset, :hidden, :checkbox, :radio')
      .val('');
    $(form).find('input:checkbox,input:radio').prop('checked', false);
    //selectは先頭選択
    $(form).find('select').prop('selectedIndex', 0);
    //flatpickr
    document.querySelectorAll('[id^=flp]').forEach((element) => {
      element._flatpickr.clear();
    });
    //custom-file-label
    if ($('.custom-file-input').length) {
      $('.custom-file-input').trigger('change');
    }
  }
  /**
   * フォーム監視開始
   * @function
   * @memberOf BaseForm
   */
  setUpFormObserver() {
    this.resetFormObserver();
    $(this.#formSelector).on('change', () => {
      this.#formObserver = true;
    });
  }
  /**
   * フォーム監視状況を返す。
   * @function
   * @memberOf BaseForm
   * @return {bool} フォームオブザーバー
   */
  isFormChanged() {
    return this.#formObserver;
  }
  /**
   * フォーム監視状況をクリアする。
   * @function
   * @memberOf BaseForm
   */
  resetFormObserver() {
    this.#formObserver = false;
  }
  /**
   * フォーム監視状況を意図的にTrueにする。
   * @function
   * @memberOf BaseForm
   */
  changeFormObserver() {
    this.#formObserver = true;
  }
  /**
   * 指定したエリア内のinput,select,textareaタグのdisabledを設定
   * @function
   * @memberOf BaseForm
   * @param {string} selector 要素が属するエリアのセレクター
   * @param {boolean} isDisabled disabled属性値
   */
  changeDisabled(selector, isDisabled) {
    const $area = $(selector);
    $area.find('input').attr('disabled', isDisabled);
    $area.find('select').attr('disabled', isDisabled);
    $area.find('textarea').attr('disabled', isDisabled);
  }
  /**
   * 必須状態の切り替えを行う。id属性縛り。
   * @function
   * @memberOf BaseForm
   * @param {string} selector 対象要素を示すセレクター
   * @param {boolean} required 状態
   */
  changeRequired(selector, required) {
    const labelSelector = _.startsWith(selector, '#')
      ? selector.substr(1)
      : null;
    if (required) {
      $(selector).prop('required', 'required');
      if (labelSelector) {
        $(`label[for="${labelSelector}"]`).addClass('required');
      }
    } else {
      $(selector).removeAttr('required');
      if (labelSelector) {
        $(`label[for="${labelSelector}"]`).removeClass('required');
      }
    }
  }
  /**
   * フォームセレクター
   * @function
   * @memberOf BaseForm
   * @return {string} フォームのセレクター
   */
  get formSelector() {
    return this.#formSelector;
  }
}
export default BaseForm;
