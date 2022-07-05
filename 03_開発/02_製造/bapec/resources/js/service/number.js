/**
 * 数値関連
 *
 * @classdesc @js/service/number.js
 */
class Number {
  /**
   *
   * @param {*} point
   * @returns
   */
  static isNumber(point) {
    const regex = new RegExp(/^[+]?[0-9]+$/);
    return regex.test(point);
  }

  static formatNumber(num) {
    let t = parseInt(num, 10);
    return t.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
  }

  static formatNumberWithSimbol(num) {
    let t = parseInt(num, 10);
    return '￥' + t.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
  }
}
export default Number;
