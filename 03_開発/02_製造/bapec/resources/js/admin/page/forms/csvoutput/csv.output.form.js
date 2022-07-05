import GridService from '@js/service/grid';
import BaseForm from '@js/app/page/forms/base.form';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import {ModalMessage, Toaster} from '@js/service/notifications';
import Messages from '@js/app/messages';
import StringService from '@js/service/string';

/**
 * CSV出力
 */
class CsvOutputForm extends BaseForm {
  // 機能ID
  #functionId;
  // リクエストURL
  #requestUrls;
  // 一覧部の定義
  #gridColDefines;
  // 取得XML
  #xmlElement = {
    sql_param: {},
    // sql_str: null,
    select_attr: {},
    // 見出し出力
    midasiFlg: true,
    // 文字コード
    encode: 'Shift-JIS'
  };

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID functionIdは、各bladeのfunctionIdを設定する（csv-output)
   * @param isDialog true:ダイアログ表示
   */
  constructor(functionId) {
    super(`#form-${functionId}`);
    this.#functionId = functionId;
    this.setUpFormObserver();
    this.#requestUrls = {
      edit: '/api/admin/csv-output/:filename/edit',
      download: '/api/admin/csv-output/download'
    };
    this.gridInstance = null;
    // 明細部（条件部）
    let colDefines = [];
    colDefines.push(
      GridService.getColDefine({
        headerName: '項目名',
        field: 'paramname',
        width: 160,
        flex: 1,
        cellClass: ['px-1', 'py-0', 'ml-1']
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '値',
        field: 'paramdata',
        minWidth: 150,
        width: 180,
        editable: false,
        flex: 1,
        cellClass: ['px-0', 'py-0', 'paramdata-cell'],
        cellRenderer: (params) =>
          `<div id="${this.#functionId}_paramdata_${params.data.data}">
                    <input type="text" class="form-control form-control-sm ${
                      this.#functionId
                    }_paramdata" data-paramdata="${params.data.data}" value="${
            params && params.data.paramdata && params.data.paramdata[0]
              ? params.data.paramdata
              : ''
          }" ${params.data.required == '1' ? 'required' : ''} maxlength="258">
                </div>`
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '注釈',
        field: 'chushaku',
        flex: 1,
        cellClass: ['px-1', 'py-0', 'pt-1'],
        cellRenderer: (params) =>
          `<div id="${this.#functionId}_chushaku_${params.data.data}">
                    ${
                      params && params.data.chushaku && params.data.chushaku[0]
                        ? params.data.chushaku
                        : ''
                    }
                </div>`
      })
    );
    this.#gridColDefines = colDefines;

    // 表示ボタンクリック
    $(`#${this.#functionId}-btn-disp`).on('click', (e) => {
      // 選択中の出力データファイル名を取得
      if (!$(`#${this.#functionId}_joken_file`).val()) {
        Toaster.showAlertToaster(
          Messages.getMessage('E.file.notselect'),
          '3000'
        );
        return false;
      }
      const joken_file_name = $(
        `#${this.#functionId}_joken_file option:selected`
      ).text();
      const url = StringService.bindParameter(this.#requestUrls.edit, {
        filename: joken_file_name
      });
      const xhrParam = new XhrParam(url);
      const successHandler = (res) => {
        // 取得したXMLファイルの内容を画面表示
        this.dispJokenInfo(res);
        this.renderGrid(res);
        this.dispJokenDetailArea();
        // フォームにバリデーション定義を適用
        super.parsleySetup(`#form-${this.#functionId}`);
        // 入力チェック
        super.validate(`#form-${this.#functionId}`);
      };
      XHRCrudMethod.get(xhrParam, successHandler);
    });

    // クリアボタンクリック
    $(`#${this.#functionId}-btn-clear`).on('click', (e) => {
      this.dispInitialize();
    });

    // 出力ボタンクリック（CSV/テンプレートファイルをダウンロード）
    // $(`#${this.#functionId}-btn-download`, `#${this.#functionId}-btn-generallsit`).on('click', (e) => {
    $('.dlbtn').on('click', (e) => {
      // 入力チェックエラー時は終了
      if (super.hasError()) {
        Toaster.showAlertToaster(fw.i18n.messages['E.input.confirm'], '3000');
        return;
      }
      // 条件ファイル実行
      const okHandler = () => {
        // ファイル出力処理
        const appendParam = this.createDownloadParameter();
        const json = this.formValueToJson();
        Object.assign(json, appendParam);
        const xhrParam = new XhrParam(`${this.#requestUrls.download}`, json);
        const successHandler = (res) => {
          if (res.errorMessage) {
            // 取得データなし
            ModalMessage.showInformation(res.errorMessage);
            return false;
          }
          const download_target = document.getElementById(
            `${this.#functionId}-btn-download-exec`
          );
          // ダウンロードリンクを初期化する
          $(`#${this.#functionId}-btn-download-exec`).empty();
          // リンクを作成しダウンロード
          const link = document.createElement('a');
          link.href = res.downloadPath;
          link.setAttribute('download', res.fileName);
          download_target.appendChild(link);
          link.click();
        };
        XHRCrudMethod.download(xhrParam, successHandler);
      };
      // --- 画面出力時
      const okListHandler = () => {
        const appendParam = this.createDownloadParameter();
        const json = this.formValueToJson();
        Object.assign(json, appendParam);
        //別Window
        window.open('', 'new_window');

        document
          .getElementById(`form-${this.#functionId}-generallist`)
          .submit();
        document.getElementsByName('generallist-param')[0].value =
          JSON.stringify(json);
      };
      if (
        e.currentTarget ==
        document.getElementById(`${this.#functionId}-btn-download`)
      ) {
        okHandler();
      } else {
        okListHandler();
      }
    });
    // 初期表示（条件データ詳細非表示）
    this.dispInitialize();
  }

  /**
   * ダウンロードパラメーター作成
   * @return {object} リクエストパラメータ(json)
   */
  createDownloadParameter() {
    // 条件パラメータを取得
    const attrList = {};
    const selectAttrList = this.#xmlElement.select_attr;
    $.each(selectAttrList, function (index, selectAttr) {
      // 条件ファイルをセット
      attrList[index] = {
        data: selectAttr.data,
        type: selectAttr.type,
        required: selectAttr.required,
        id: selectAttr.id,
        paramname: selectAttr.paramname,
        paramdata: selectAttr.paramdata,
        chushaku: selectAttr.chushaku
      };
    });
    // 入力値を取得
    let paramList = {};
    let paramDataValue = [];
    const paramdataList = document.getElementsByClassName(
      `${this.#functionId}_paramdata`
    );

    $.each(this.#xmlElement.select_attr, function (index, selectAttr) {
      $.each(paramdataList, function (index, input_paramdata) {
        // パラメータIDが一致する場合、画面設定値をSQLパラメータに設定
        if (input_paramdata.dataset.paramdata == selectAttr.data) {
          paramList[selectAttr.id] = input_paramdata.value;
          paramDataValue.push(input_paramdata.value);
          return false; // 一致した場合は以降の処理は不要のため次のパラメータへ
        }
      });
    });

    // 入力値を反映
    const json = {
      sql_param: paramList,
      // sql: this.#xmlElement.sql_str,
      paramdata_value: paramDataValue,
      joken_file_name: $(
        `#${this.#functionId}_joken_file option:selected`
      ).text(),
      midasi_flag: this.#xmlElement.midasiFlg,
      char_code: this.#xmlElement.encode
    };
    return json;
  }
  /**
   * 条件ファイル情報表示
   * @param {object} res 検索結果
   */
  dispJokenInfo(res) {
    const jokenInfo = res.data;
    // 出力内容詳細
    $(`#${this.#functionId}_contents_detail`).text(jokenInfo.CONTENT);
    // XML情報保持
    this.#xmlElement.sql_param = jokenInfo.SQLPARAM;
    // this.#xmlElement.sql_str = jokenInfo.SQLSTR;
    this.#xmlElement.select_attr = jokenInfo.SELECTATTR;
    this.#xmlElement.midasiFlg = jokenInfo.MIDASIFLG;
    this.#xmlElement.encode = jokenInfo.ENCODE;
  }
  /**
   * 条件一覧テーブル再描画
   * @param {object} res 検索結果
   */
  renderGrid(res) {
    // 描画準備
    if (!this.gridInstance) {
      this.gridInstance = new GridService(`${this.#functionId}-grid`);
    }
    // 描画
    this.gridInstance.render(this.#gridColDefines, res.data.SELECTATTR, {
      domLayout: 'normal'
    });
  }
  /**
   * 画面初期表示（条件データ詳細非表示）
   */
  dispInitialize() {
    this.resetFormObserver();
    this.clearForm();
    // 条件データ詳細部非表示
    $(`#${this.#functionId}-joken-detail-area`).hide();
    // 出力データ未選択
    $(`#${this.#functionId}_joken_file`)
      .val('')
      .trigger('change');
    // 条件データ詳細非表示時は活性化（出力データ選択／表示ボタン／ファイル選択）
    $(`#${this.#functionId}_joken_file`).prop('disabled', false);
    $(`#${this.#functionId}-btn-disp`).prop('disabled', false);
    super.resetValidateAll('#form-csv_output');
  }
  /**
   * 条件データ詳細表示
   */
  dispJokenDetailArea() {
    // 条件データ詳細表示時は非活性化（出力データ選択／表示ボタン／ファイル選択）
    $(`#${this.#functionId}_joken_file`).prop('disabled', true);
    $(`#${this.#functionId}-btn-disp`).prop('disabled', true);
    $(`#${this.#functionId}-joken-detail-area`).show();
  }
}
export default CsvOutputForm;
