import BaseForm from '@js/app/page/forms/base.form';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import GridService from '@js/service/grid';
import {Toaster} from '@js/service/notifications';
import Messages from '@js/app/messages';
/**
 * 取り込み画面基底クラス
 */
class ImportBaseForm extends BaseForm {
  // 画面処理ID
  #functionId;
  // 一覧部の定義
  #gridColDefines;
  // URLs
  #requestUrls;

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   * @param {object} requestUrls リクエストURL
   */
  constructor(functionId, requestUrls) {
    super(`#form-${functionId}-upload`);
    this.#functionId = functionId;
    this.#requestUrls = requestUrls;
    this.#gridColDefines = this.getGridColDefine();

    /**
     * アップロードボタン押下
     */
    $(`#${this.#functionId}_upload_btn`).on('click', (e) => {
      // ファイル選択チェック
      const upload_file_info = document.getElementById('upload_data').files[0];
      if (!upload_file_info) {
        Toaster.showAlertToaster(
          Messages.getMessage('E.file.notselect'),
          '3000'
        );
        return false;
      }
      // ファイルの種類をチェック（csvファイルのみ可）
      const file_split = upload_file_info.name.split('.');
      const file_ext = file_split[file_split.length - 1];
      if (file_ext.toLowerCase() != 'csv') {
        Toaster.showAlertToaster(Messages.getMessage('E.file.unuse'), '3000');
        return false;
      }

      // ファイルアップロード処理
      const appendParam = {
        attaches: {
          name: 'upload_file',
          file: upload_file_info
        }
      };
      const param = {};
      Object.assign(param, appendParam);
      const xhrParam = new XhrParam(this.#requestUrls.upload, param);
      const successHandler = (res) => {
        this.resetForm();
        $('.custom-file-label').text('');

        // 実行ログ検索
        this.searchLog();
      };
      XHRCrudMethod.upload(xhrParam, successHandler);
    });

    // リロードボタンクリック
    $(`#${this.#functionId}-btn-reload`).on('click', (e) => {
      this.searchLog();
    });

    this.searchLog();
  }

  /**
   * 実行ログを検索する
   */
  searchLog() {
    // 実行ログ検索
    const xhrParam = new XhrParam(this.#requestUrls.search);
    const successHandler = (res) => this.renderGrid(res);
    XHRCrudMethod.get(xhrParam, successHandler);
  }
  /**
   * 一覧の列定義を取得する。
   * @return {array} 列定義体
   */
  getGridColDefine() {
    let colDefines = [];
    colDefines.push(
      GridService.getColDefine({
        headerName: 'ID',
        field: 'id',
        width: 50
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '取込実行日時',
        field: 'upload_date',
        width: 100
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: 'ステータス',
        field: 'status_name',
        width: 80
        // cellRenderer: (params) =>
        //   switc( params.data.status) {
        //   case (fw.define.ImportStatusDefine.SUCCESS):
        //     '正常終了';
        //   }
        //     ?
        //     : 'エラーあり'
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '取り込みファイル',
        field: 'file_name',
        cellRenderer: (params) =>
          params.data.file_name
            ? `<a href="/api/admin/import/${params.data.id}/file" download>${params.data.file_name}</a>`
            : ``
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: 'ログファイル',
        field: 'log_file_name',
        cellRenderer: (params) =>
          params.data.log_file_name
            ? `<a href="/api/admin/import/${params.data.id}/log" download>${params.data.log_file_name}</a>`
            : ``
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: 'メッセージ',
        field: 'message'
      })
    );
    return colDefines;
  }

  /**
   * Grid描画
   */
  renderGrid(res) {
    // 描画準備
    if (!this.gridInstance) {
      this.gridInstance = new GridService(`${this.#functionId}-grid`);
    }
    // 描画
    let gridOptions = {
      onRowDoubleClicked: (row) => {
        return;
      }
    };
    this.gridInstance.render(this.#gridColDefines, res.data, gridOptions);
  }
}

export {ImportBaseForm};
