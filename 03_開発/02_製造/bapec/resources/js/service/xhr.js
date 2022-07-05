import {Spinner, SpinnerParam} from './spinner.js';
import {ModalMessage, Toaster} from './notifications.js';

/**
 * XmlHttpRequestに関する処理を定義する。
 * @classdesc @js/service/xhr.js
 */
class XHRCrudMethod {
  /**
   * GETメソッドを実行する。<br>
   * 実行結果が正常の場合、登録完了トーストを表示する。<br>
   * 実行結果がエラーの場合、エラーメッセージを表示する。
   * @function
   * @memberof XHRCrudMethod
   * @param {XhrParam} xhrParam XHRパラメータ―
   * @param {function} successHandler リクエスト成功時のハンドラー
   * @return {object} ?
   */
  static get(xhrParam, successHandler) {
    return XHR.get(xhrParam)
      .then((res) => {
        successHandler(res);
      })
      .catch((err) => {
        this.fail(err);
      });
  }
  /**
   * 登録処理を実行する。<br>
   * 実行結果が正常の場合、登録完了トーストを表示する。<br>
   * 実行結果がエラーの場合、エラーメッセージを表示する。
   * @function
   * @memberof XHRCrudMethod
   * @param {XhrParam} xhrParam XHRパラメータ―
   * @param {function} successHandler リクエスト成功時のハンドラー
   * @param boolean isShowToaster 完了後にトースト表示を行うかどうか
   * @return {object} ?
   */
  static store(xhrParam, successHandler, isShowToaster = true) {
    return XHR.post(xhrParam)
      .then((res) => {
        if (isShowToaster) {
          Toaster.showRegistToaster();
        }
        successHandler(res);
      })
      .catch((err) => {
        this.fail(err);
      });
  }
  /**
   * 更新処理を実行する。<br>
   * 実行結果が正常の場合、更新完了トーストを表示する。<br>
   * 実行結果がエラーの場合、エラーメッセージを表示する。
   * @function
   * @memberof XHRCrudMethod
   * @param {XhrParam} xhrParam XHRパラメータ―
   * @param {function} successHandler リクエスト成功時のハンドラー
   * @param boolean isShowToaster 完了後にトースト表示を行うかどうか
   * @return {object} ?
   */
  static update(xhrParam, successHandler, isShowToaster = true) {
    return XHR.put(xhrParam)
      .then((res) => {
        if (isShowToaster) {
          Toaster.showUpdateToaster();
        }
        successHandler(res);
      })
      .catch((err) => {
        this.fail(err);
      });
  }
  /**
   * 削除処理を実行する。<br>
   * 実行結果が正常の場合、削除完了トーストを表示する。<br>
   * 実行結果がエラーの場合、エラーメッセージを表示する。
   * @function
   * @memberof XHRCrudMethod
   * @param {XhrParam} xhrParam XHRパラメータ―
   * @param {function} successHandler リクエスト成功時のハンドラー
   * @param boolean isShowToaster 完了後にトースト表示を行うかどうか
   * @return {pbject} ?
   */
  static delete(xhrParam, successHandler, isShowToaster = true) {
    return XHR.destroy(xhrParam)
      .then((res) => {
        if (isShowToaster) {
          Toaster.showDeleteToaster();
        }
        successHandler(res);
      })
      .catch((err) => {
        this.fail(err);
      });
  }

  static upload(xhrParam, successHandler, isShowToaster = true) {
    return XHR.upload(xhrParam)
      .then((res) => {
        if (isShowToaster) {
          Toaster.showRegistToaster();
        }
        successHandler(res);
      })
      .catch((err) => {
        this.fail(err);
      });
  }

  static uploads(xhrParam, successHandler, isShowToaster = true) {
    return XHR.uploads(xhrParam)
      .then((res) => {
        if (isShowToaster) {
          Toaster.showRegistToaster();
        }
        successHandler(res);
      })
      .catch((err) => {
        this.fail(err);
      });
  }

  static download(xhrParam, successHandler, isShowToaster = true) {
    return XHR.download(xhrParam)
      .then((res) => {
        try {
          successHandler(res);
        } catch (err) {
          console.error(err);
        }
      })
      .catch((err) => {
        this.fail(err);
      });
  }

  /**
   * レスポンス失敗ハンドリング定義
   */
  static fail(err, errorHandler = null) {
    // 認証エラー or セッションタイムアウト
    if (err.status === 401 || err.status === 419) {
      // ログアウトする
      window.location.href = '/login';
      return;
    } else {
      if (err.status === 500) {
        if (!err.response) {
          ModalMessage.showDanger(err.message);
        } else {
          if (err.response.body) {
            ModalMessage.showDanger(err.response.body.message);
          } else {
            ModalMessage.showDanger(err.response.error);
          }
        }
      } else {
        // if (err.status === 422) {//validation
        // メッセージをトースト表示
        if (err.response && err.response.body && err.response.body.errors) {
          let notifyMessage = '';
          let msgObj = err.response.body.errors;
          if (msgObj instanceof Object) {
            // メッセージのキーを配列で取得
            const messageKeys = Object.keys(msgObj);
            // メッセージのkeyに'isConfirm'が存在し、要素がそれのみ、かつエラーハンドラーが指定されている場合
            if (
              messageKeys.indexOf('isConfirm') > -1 &&
              messageKeys.length == 1 &&
              errorHandler
            ) {
              // トースト表示ではなくerrorHandlerを実行する
              errorHandler(msgObj['isConfirm']);
              return;
            }
            // キー毎にメッセージを結合する
            messageKeys.forEach((key) => {
              let message = msgObj[key];
              if (notifyMessage) {
                notifyMessage += '<br>';
              }
              notifyMessage += message;
            });
          } else {
            notifyMessage = msgObj;
          }
          Toaster.showAlertToaster(notifyMessage);
          return;
          // }
        } else {
          if (!err.response) {
            Toaster.showAlertToaster(err.message);
          } else {
            if (err.response.body) {
              Toaster.showAlertToaster(err.response.body.message);
            } else {
              Toaster.showAlertToaster(err.response.error);
            }
          }
        }
      }
    }
  }
}
/**
 * 非同期通信実行クラス
 * @classdesc @js/service/xhr.js
 */
class XHR {
  /**
   * csrf token
   * @memberof XHR
   */
  static csrf = $('meta[name="csrf-token"]').attr('content');
  /**
   * リクエスト要求メソッドパラメーターチェック
   * @function
   * @memberof XHR
   * @param {XhrParam} xhrParam XHRパラメータ―
   * @throws {SyntaxError} 引数の型がXhrParamではないとき、または、urlパラメータが未設定の場合発生する。
   */
  static checkConfig(xhrParam) {
    if (!(xhrParam instanceof XhrParam)) {
      throw new SyntaxError('XHRは、XhrParam型で実行してください。');
    }
    if (!xhrParam.url) {
      throw new SyntaxError('URLが指定されてません。');
    }
  }
  /**
   * GETメソッド実行
   * @function
   * @memberof XHR
   * @param {XhrParam} xhrParam XHRパラメータ―
   * @param {boolean} responsePlain レスポンスをJSON.parseしない
   * @return {pbject} ?
   */
  static get(xhrParam, responsePlain = false) {
    XHR.checkConfig(xhrParam);
    const spinner = new Spinner(xhrParam.spinnerParam);
    spinner.show();
    return new Promise((resolve, reject) => {
      window.superagent
        .get(xhrParam.url)
        .set('Content-Type', 'application/json')
        .set('X-Requested-With', 'XMLHttpRequest')
        .set('X-CSRF-TOKEN', XHR.csrf)
        .query(xhrParam.params)
        .end((err, res) => {
          spinner.hide();
          if (err) {
            reject(err);
          } else {
            if (responsePlain) {
              resolve(res.text);
            } else {
              resolve(JSON.parse(res.text));
            }
          }
        });
    });
  }
  /**
   * POSTメソッド実行
   * @function
   * @memberof XHR
   * @param {XhrParam} xhrParam XHRパラメータ―
   * @return {pbject} ?
   */
  static post(xhrParam) {
    this.checkConfig(xhrParam);
    const spinner = new Spinner(xhrParam.spinnerParam);
    spinner.show();
    return new Promise((resolve, reject) => {
      window.superagent
        .post(xhrParam.url)
        .set('Content-Type', 'application/json')
        .set('X-Requested-With', 'XMLHttpRequest')
        .set('X-CSRF-TOKEN', this.csrf)
        .send(xhrParam.params)
        .end((err, res) => {
          spinner.hide();
          if (err) {
            reject(err);
          } else {
            resolve(JSON.parse(res.text));
          }
        });
    });
  }
  /**
   * PUTメソッド実行
   * @function
   * @memberof XHR
   * @param {XhrParam} xhrParam XHRパラメータ―
   * @param {XhrParam} xhrParam XHRパラメータ―
   * @return {pbject} ?
   */
  static put(xhrParam) {
    this.checkConfig(xhrParam);
    const spinner = new Spinner(xhrParam.spinnerParam);
    spinner.show();
    return new Promise((resolve, reject) => {
      window.superagent
        .put(xhrParam.url)
        // .set('Content-Type', 'application/json')
        .set('X-Requested-With', 'XMLHttpRequest')
        .set('X-CSRF-TOKEN', this.csrf)
        .send(xhrParam.params)
        .end((err, res) => {
          spinner.hide();
          if (err) {
            reject(err);
          } else {
            resolve(JSON.parse(res.text));
          }
        });
    });
  }
  /**
   * DELETEメソッド実行
   * @function
   * @memberof XHR
   * @param {XhrParam} xhrParam XHRパラメータ―
   * @return {pbject} ?
   */
  static destroy(xhrParam) {
    this.checkConfig(xhrParam);
    const spinner = new Spinner(xhrParam.spinnerParam);
    spinner.show();
    return new Promise((resolve, reject) => {
      window.superagent
        .delete(xhrParam.url)
        .set('Content-Type', 'application/json')
        .set('X-Requested-With', 'XMLHttpRequest')
        .set('X-CSRF-TOKEN', this.csrf)
        .send(xhrParam.params)
        .end((err, res) => {
          spinner.hide();
          if (err) {
            reject(err);
          } else {
            resolve(JSON.parse(res.text));
          }
        });
    });
  }
  /**
   * UPLOADメソッド実行
   *
   * @param {XhrParam} xhrParam XHRパラメータ―
   */
  static upload(xhrParam) {
    this.checkConfig(xhrParam);
    const spinner = new Spinner(xhrParam.spinnerParam);
    spinner.show();
    const formData = new FormData();
    $.each(xhrParam.params, (key, value) => {
      if (key == 'filedata') {
        // ファイルの場合
        formData.append(value['name'], value['file']);
      } else {
        // MultipartDataの場合、日本語が文字化けするのでURLエンコードを行う。
        formData.append(key, value);
      }
    });

    if (xhrParam.params.attaches) {
      formData.append(
        xhrParam.params.attaches.name,
        xhrParam.params.attaches.file
      );
    }

    return new Promise((resolve, reject) => {
      window.superagent
        .post(xhrParam.url)
        .set('X-CSRF-TOKEN', this.csrf)
        .set('X-Requested-With', 'XMLHttpRequest')
        .timeout({
          response: 180000,
          deadline: 180000
        })
        .send(formData)
        .end((err, res) => {
          spinner.hide();
          if (err) {
            reject(err);
          } else {
            resolve(JSON.parse(res.text));
          }
        });
    });
  }
  /**
   * 複数ファイル対応版 UPLOADメソッド実行
   *
   * @param {XhrParam} xhrParam XHRパラメータ―
   */
  static uploads(xhrParam) {
    this.checkConfig(xhrParam);
    const spinner = new Spinner(xhrParam.spinnerParam);
    spinner.show();
    const formData = new FormData();
    $.each(xhrParam.params, (key, value) => {
      if (key.startsWith('filedata')) {
        // ファイルの場合
        formData.append(value['name'], value['file']);
      } else {
        // MultipartDataの場合、日本語が文字化けするのでURLエンコードを行う。
        formData.append(key, value);
      }
    });

    if (xhrParam.params.attaches) {
      formData.append(
        xhrParam.params.attaches.name,
        xhrParam.params.attaches.file
      );
    }

    return new Promise((resolve, reject) => {
      window.superagent
        .post(xhrParam.url)
        .set('X-CSRF-TOKEN', this.csrf)
        .set('X-Requested-With', 'XMLHttpRequest')
        .timeout({
          response: 180000,
          deadline: 180000
        })
        .send(formData)
        .end((err, res) => {
          spinner.hide();
          if (err) {
            reject(err);
          } else {
            resolve(JSON.parse(res.text));
          }
        });
    });
  }
  /**
   * DOWNLOADメソッド実行
   *
   * @param {XhrParam} xhrParam XHRパラメータ―
   */
  static download(xhrParam) {
    this.checkConfig(xhrParam);
    const spinner = new Spinner(xhrParam.spinnerParam);
    spinner.show();
    return new Promise((resolve, reject) => {
      window.superagent
        .post(xhrParam.url)
        .set('Content-Type', 'application/json')
        .set('X-Requested-With', 'XMLHttpRequest')
        .set('X-CSRF-TOKEN', this.csrf)
        .send(xhrParam.params)
        .responseType('json')
        .end((err, res) => {
          spinner.hide();
          if (err) {
            reject(err);
          } else {
            resolve(res.body);
          }
        });
    });
  }
}
/**
 * XHRパラメーター
 * @classdesc path: @js/servcie/xhr.js
 */
class XhrParam {
  /**
   * スピナー表示設定
   * @memberof XhrParam
   */
  #spinnerParam = new SpinnerParam();
  /**
   * 添付ファイル
   * @memberof XhrParam
   */
  #attaches;
  /**
   * URL
   * @memberof XhrParam
   */
  #url;
  /**
   * リクエストパラメーター
   * @memberof XhrParam
   */
  #params;
  /**
   * コンストラクタ
   *
   * @param {string} url URL
   * @param {object} params リクエストパラメータ－
   * @param {array} attaches 添付ファイル
   */
  constructor(url, params = {}, attaches = []) {
    this.#url = url;
    this.#params = params;
    this.#attaches = attaches;
  }
  /**
   * スピナー表示設定
   * @return {object} スピナー表示設定
   */
  get spinnerParam() {
    return this.#spinnerParam;
  }
  /**
   * 添付ファイルを返す。
   * @return {array} 点pファイル
   */
  get attaches() {
    return this.#attaches;
  }
  /**
   * URLを返す。
   * @return {string} URL
   */
  get url() {
    return this.#url;
  }
  /**
   * リクエストパラメータを返す。
   * @return {object} リクエストパラメーター
   */
  get params() {
    return this.#params;
  }
}
export {XHR, XhrParam, XHRCrudMethod};
