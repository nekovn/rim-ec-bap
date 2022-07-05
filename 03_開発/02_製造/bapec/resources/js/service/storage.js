/**
 * ブラウザストレージサービス
 *
 * @classdesc @js/service/storage.js
 */
class StorageService {
  /**
   * ローカルストレージに値をセットする。
   * @function
   * @memberof StorageService
   * @param {string} keyName 登録するキー
   * @param {string} value 登録する値
   */
  static setLocalStorage(keyName, value) {
    localStorage.setItem(keyName, value);
  }
  /**
   * ローカルストレージから値を取得する。
   * @function
   * @memberof StorageService
   * @param {string} keyName 取得するキー
   * @return {string} 取得した値
   */
  static getLocalStorage(keyName) {
    return localStorage.getItem(keyName);
  }
  /**
   * ローカルストレージから設定値を削除する。
   * @function
   * @memberof StorageService
   * @param {string} keyName 削除するキー
   */
  static removeLocalStorage(keyName) {
    localStorage.removeItem(keyName);
  }
  /**
   * ローカルストレージをクリアする。
   * @function
   * @memberof StorageService
   */
  static clearLocalStorage() {
    localStorage.clear();
  }
  /**
   * セッションストレージに値をセットする。
   * @function
   * @memberof StorageService
   * @param {string} keyName 登録するキー
   * @param {string} value 登録する値
   */
  static setSessionStorage(keyName, value) {
    sessionStorage.setItem(keyName, value);
  }
  /**
   * セッションストレージから値を取得する。
   * @function
   * @memberof StorageService
   * @param {string} keyName 取得するキー
   * @return {string} 取得した値
   */
  static getSessionStorage(keyName) {
    return sessionStorage.getItem(keyName);
  }
  /**
   * セッションストレージから設定値を削除する。
   * @function
   * @memberof StorageService
   * @param {string} keyName 削除するキー
   */
  static removeSessionStorage(keyName) {
    sessionStorage.removeItem(keyName);
  }
  /**
   * セッションストレージをクリアする。
   * @function
   * @memberof StorageService
   */
  static clearSessionStorage() {
    sessionStorage.clear();
  }
}
export default StorageService;
