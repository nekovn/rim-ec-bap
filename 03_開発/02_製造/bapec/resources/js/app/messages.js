import StringService from '@js/service/string';

/**
 * メッセージを表示する。
 *
 * @classdesc path: @js/base/message.js
 */
class Messages {
  /**
   * メッセージの動的パラメータ部(:key、keyはパラメータ名)に引数のパラメーターを設定する。

   * @function
   * @memberOf Messages
   * @param {string} messageKey メッセージキー
   * @param {array} parameters メッセージ動的パラメータ配列
   * @return {string} メッセージ文字列
   */
  static getMessage = (messageKey, parameters) => {
    return StringService.bindParameter(
      fw.i18n.messages[messageKey],
      parameters
    );
  };
}
export default Messages;
