/**
 * スピナーを表示する
 *
 * @classdesc @js/service/spinner.js
 */
class Spinner {
  /**
   * 表示時のパラメーター
   * @memberof Spinner
   */
  spinnerParam = null;
  /**
   * コンストラクタ
   *
   * @param {SpinnerParam} spinnerParam 表示時のパラメーター
   */
  constructor(spinnerParam) {
    this.spinnerParam = spinnerParam;
  }
  /**
   * プログレススピナーを表示する。
   * @function
   * @memberof Spinner
   */
  show() {
    // プログレス表示初期表示
    if (this.spinnerParam.isHide()) {
      return;
    }

    if (!this.spinnerParam.areaSelector) {
      $('.progress-spinner-overlay').show();
      return;
    }

    let loading = $('.progress-spinner-overlay:last').clone();
    let area = $(`#${this.spinnerParam.areaSelector}`);

    loading.attr('id', this.spinnerParam.spinnerId);

    // style追加
    $.each(this.spinnerParam.styles, (key, val) => {
      loading.css(key, val);
    });

    // class追加
    $.each(this.spinnerParam.classes, (key, val) => {
      loading.addClass(val);
    });

    $(area).append(loading);

    $(`#${this.spinnerParam.elementId}`).show();
  }
  /**
   * プログレスを非表示にする。
   * @function
   * @memberof Spinner
   */
  hide() {
    if (!this.spinnerParam.spinnerId) {
      $('.progress-spinner-overlay').hide();
      return;
    }
    $(`#${this.spinnerParam.spinnerId}`).remove();
  }
}
/**
 * スピナー表示用パラメーター
 * @classdesc @js/service/spinner.js
 */
class SpinnerParam {
  /**
   * スピナー表示判定
   * @memberof SpinnerParam
   */
  show;
  /**
   * スピナー追加エリアを指すセレクター
   * @memberof SpinnerParam
   */
  areaSelector;
  /**
   * スピナー追加エリアのスピナーに付与するセレクター
   * @memberof SpinnerParam
   */
  spinnerId;
  /**
   * スピナー追加エリアのスピナーに付与するstyle属性
   * @memberof SpinnerParam
   */
  styles = {};
  /**
   * スピナー追加エリアのスピナーに付与するclass属性
   * @memberof SpinnerParam
   */
  classes = {};
  /**
   * コンストラクタ
   *
   * @param {boolean} show スピナー表示判定
   */
  constructor(show = true) {
    this.show = show;
  }
  /**
   * スピナー表示エリアを指定する。
   * @function
   * @memberof SpinnerParam
   * @param {string} areaSelector スピナー追加エリアを指すセレクター
   * @param {string} spinnerId スピナー追加エリアのスピナーに付与するセレクター
   * @param {object} styles スピナー追加エリアのスピナーに付与するstyle属性
   * @param {object} classes スピナー追加エリアのスピナーに付与するclass属性
   */
  setShowArea(areaSelector, spinnerId, styles = {}, classes = {}) {
    this.areaSelector = areaSelector;
    this.spinnerId = spinnerId;
    this.styles = styles;
    this.classes = classes;
  }
  /**
   * スピナー表示エリアセレクターを返す。
   * @function
   * @memberof SpinnerParam
   * @return {string} スピナー表示エリアセレクター
   */
  get areaSelector() {
    return this.areaSelector;
  }
  /**
   * スピナーに付与するIDを返す。
   * @function
   * @memberof SpinnerParam
   * @return {string} スピナーに付与するID
   */
  get spinnerId() {
    return this.spinnerId;
  }
  /**
   * スピナーに付与するスタイル属性を返す。
   * @function
   * @memberof SpinnerParam
   * @return {object} スピナーに付与するスタイル属性
   */
  get styles() {
    return this.styles;
  }
  /**
   * スピナーに付与するクラス属性を返す。
   * @function
   * @memberof SpinnerParam
   * @return {object} スピナーに付与するクラス属性
   */
  get classes() {
    return this.classes;
  }
  /**
   * スピナー表示状態を返す。
   * @function
   * @memberof SpinnerParam
   * @return {boolean} true: 表示中, false: 非表示
   */
  isShow() {
    return this.show;
  }
  /**
   * スピナー非表示状態を返す。
   * @function
   * @memberof SpinnerParam
   * @return {boolean} true: 非表示, false: 表示中
   */
  isHide() {
    return !this.isShow();
  }
}
export {Spinner, SpinnerParam};
