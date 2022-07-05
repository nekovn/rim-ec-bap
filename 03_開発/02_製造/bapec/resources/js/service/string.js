/**
 * 文字列に関するユーティリティサービス
 *
 * @classdesc @js/service/string.js
 */
class StringService {
  /**
   * オブジェクトをjson文字列に変換する。オブジェクト内関数定義保持。
   * @function
   * @memberof StringService
   * @param {object} object json変換対象オブジェクト
   * @return {string} json文字列
   */
  static jsonStringfy(object) {
    const replacer = function replacer(k, v) {
      if (typeof v === 'function') {
        return v.toString();
      }

      return v;
    };
    return JSON.stringify(object, replacer);
  }
  /**
   * JSON.parseする際、関数を適用する。
   * @function
   * @memberof StringService
   * @param {string} json json文字列
   * @rturn {object} javascriptオブジェクト
   */
  static jsonParse(json) {
    const reviver = function (k, v) {
      if (typeof v === 'string' && v.match(/^function/)) {
        return Function.call(this, 'return ' + v)();
      }
      return v;
    };

    return JSON.parse(json, reviver);
  }
  /**
   * 文字列のパラメータ部にバインドパラメータを設定する。
   * @function
   * @memberof StringService
   * @param {string} source パラメータ適用文字列
   * @param {array} parameters バインドパラメータ
   * @return {string} バインドパラメータを適用した文字列
   */
  static bindParameter(source, parameters) {
    if (_.isEmpty(parameters)) {
      return source;
    }
    let string = source;
    Object.keys(parameters).forEach((key) => {
      string = string.replace(`:${key}`, parameters[key]);
    });

    return string;
  }
  /**
   * 全角数字を半角数値に変換
   * @param {string} value
   * @return {string}
   */
  static convertHankakuNum(value) {
    return value.replace(/[０-９]/g, function (s) {
      return String.fromCharCode(s.charCodeAt(0) - 0xfee0);
    });
  }
}
export default StringService;
