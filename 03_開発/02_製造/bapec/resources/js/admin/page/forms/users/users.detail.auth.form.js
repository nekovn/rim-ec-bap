import BaseForm from '@js/app/page/forms/base.form';
import GridService from '@js/service/grid';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import AnimateService from '@js/service/animate';
import {ModalMessage} from '@js/service/notifications';

/**
 * ユーザ権限マスタ用
 */
class UsersDetailAuthForm extends BaseForm {
  // 一覧部の定義
  #gridUserAuthColDefine;
  // グリッドのインスタンス
  #gridUserAuthInstance = null;

  #updateKey = {
    id: ''
  };

  #functionId;
  #requestUrls;

  #COL_READ = 'has_read';
  #COL_UPDATE = 'has_update';
  #COL_REPORT = 'has_report_output';

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    super(`#form-${functionId}-detail2`);
    this.#functionId = functionId;
    this.setUpFormObserver();

    this.#requestUrls = {
      edit: '/api/admin/users/edit/auth',
      update: '/api/admin/users/update/auth'
    };

    //権限用
    let colDefines = [];
    colDefines.push(
      GridService.getColDefine({
        headerName: '処理名',
        field: 'name',
        // width: 250,
        sortable: false
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '閲覧権限',
        field: 'has_read',
        width: 120,
        cellClass: ['text-center'],
        headerClass: ['text-center'],
        sortable: false,
        cellRenderer: UsersDetailAuthForm.checkboxRenderer,
        cellRendererParams: {name: this.#COL_READ},
        headerComponentParams: {
          template: GridService.agGridCheckboxHeader(this.#COL_READ)
        }
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '更新権限',
        field: 'has_update',
        width: 120,
        cellClass: ['text-center'],
        headerClass: ['text-center'],
        sortable: false,
        cellRenderer: UsersDetailAuthForm.checkboxRenderer,
        cellRendererParams: {name: this.#COL_UPDATE},
        headerComponentParams: {
          template: GridService.agGridCheckboxHeader(this.#COL_UPDATE)
        }
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '帳票出力権限',
        field: 'has_report_output',
        width: 120,
        cellClass: ['text-center'],
        headerClass: ['text-center'],
        cellRenderer: UsersDetailAuthForm.checkboxRenderer,
        cellRendererParams: {name: this.#COL_REPORT},
        headerComponentParams: {
          template: GridService.agGridCheckboxHeader(this.#COL_REPORT)
        }
      })
    );

    this.#gridUserAuthColDefine = colDefines;

    /**
     * 一覧画面から更新画面表示発火イベント
     */
    $(`#${functionId}-detail-area2`).on(
      'emit-simple-crud-detail2-update-show',
      (e, param) => {
        this.resetForm();

        const xhrParam = new XhrParam(`${this.#requestUrls.edit}/${param.id}`);
        const successHandler = (res) => {
          this.#updateKey.id = param.id; //m_user.id
          // this.#animateService.subAreaFadeIn(
          //   `${this.#functionId}-detail-area2`
          // );
          AnimateService.forward(`${this.#functionId}-detail-area2`);
          this.renderUserauthGrid(res);
        };
        XHRCrudMethod.get(xhrParam, successHandler);
      }
    );
    /**
     * 更新ボタン押下
     */
    $(`#${this.#functionId}-btn-update2`).on('click', (e) => {
      //入力チェック
      // super.validate();
      if (!this.isFormChanged()) {
        return;
      }
      ModalMessage.showModifyConfirm(
        ModalMessage.MODIFY_MODE.UPD,
        null,
        this.doUpdate
      );
    });
    /**
     * 戻るボタン押下
     */
    $(`#${this.#functionId}-btn-back2`).on('click', (e) => {
      const back = () => {
        this.resetFormObserver();
        $(`#${this.#functionId}-list-area`).trigger(
          'emit-simple-crud-list-show',
          {refresh: false}
        );
      };
      if (this.isFormChanged()) {
        ModalMessage.showConfirm(
          `<p>${fw.i18n.messages['C.changeclose.confirm']}</p>`,
          back
        );
        return;
      }
      back();
    });
    /** gridヘッダーcheckbox */
    $(document).on('change', '.ag-header-checkbox', (e) => {
      const elem = e.currentTarget;
      const name = elem.dataset.name;
      const chk = elem.checked;
      let headChks;
      if (chk) {
        //更新、帳票権限のときは、閲覧権限もチェック
        if (name != this.#COL_READ) {
          headChks = document.querySelectorAll(
            '[data-name="' + this.#COL_READ + '"].ag-header-checkbox'
          );
          headChks[0].checked = chk;
          GridService.setHeaderCheckboxClick(headChks[0]);
        }
      } else {
        if (name == this.#COL_READ) {
          headChks = document.querySelectorAll(
            '[data-name="' + this.#COL_UPDATE + '"].ag-header-checkbox'
          );
          headChks[0].checked = chk;
          GridService.setHeaderCheckboxClick(headChks[0]);
          headChks = document.querySelectorAll(
            '[data-name="' + this.#COL_REPORT + '"].ag-header-checkbox'
          );
          headChks[0].checked = chk;
          GridService.setHeaderCheckboxClick(headChks[0]);
        }
      }
    });

    $(document).on('change', '.ag-child-checkbox', (event) => {
      const chk = event.currentTarget.checked;
      const pgcd = event.currentTarget.dataset.pgcd;
      if (chk) {
        //更新、帳票権限のときは、閲覧権限もチェック
        if (event.currentTarget.name != this.#COL_READ) {
          $('#has_read-' + pgcd).prop('checked', true);
          GridService.setHeaderCheck('has_read');
        }
      } else {
        if (event.currentTarget.name == this.#COL_READ) {
          $('#has_update-' + pgcd).prop('checked', false);
          $('#has_report_output-' + pgcd).prop('checked', false);
          GridService.setHeaderCheck(this.#COL_UPDATE);
          GridService.setHeaderCheck(this.#COL_REPORT);
        }
      }
    });
  }

  /**
   * 更新処理
   */
  doUpdate = () => {
    //送信データ作成
    let elems = document.querySelectorAll("input[name='has_read']");
    const returnJson = [];
    for (let i = 0; i < elems.length; i++) {
      if (!elems[i].checked) {
        continue;
      }
      const pgcd = elems[i].dataset.pgcd;
      const data = {};
      data['program_cd'] = pgcd;
      const celems = document.querySelectorAll('[data-pgcd="' + pgcd + '"');
      for (let j = 0; j < celems.length; j++) {
        if (celems[j].name != 'has_read') {
          data[celems[j].name] = celems[j].checked;
        }
      }
      returnJson.push(data);
    }
    const requestParam = returnJson; //$(this.formSelector).serialize();
    const xhrParam = new XhrParam(
      `${this.#requestUrls.update}/${this.#updateKey.id}`,
      requestParam
    );
    const successHandler = (res) => {
      this.resetFormObserver();
    };
    XHRCrudMethod.update(xhrParam, successHandler);
  };

  renderUserauthGrid(res) {
    if (!this.#gridUserAuthInstance) {
      this.#gridUserAuthInstance = new GridService(
        `${this.#functionId}-auth-grid`
      );
    }
    // 描画
    let gridOptions = {
      sortable: false,
      components: {
        agColumnHeader: null
      },
      rowSelection: 'multiple'
    };
    this.#gridUserAuthInstance.render(
      this.#gridUserAuthColDefine,
      res['data-userauth'],
      gridOptions
    );
  }

  /**
   * ag-gridにチェックボックスを表示する
   * @param params:value:グリッドからの値、その他：colDefオプション.cellRendererParamsで設定される値。
   * datas:data-[elem]属性に、列名の値がセットされる
   *
   */
  static checkboxRenderer(params) {
    let operatorValue = params.value;
    const input = document.createElement('input');
    input.type = 'checkbox';
    input.value = 1;
    input.name = params.name;
    input.id = params.name + '-' + params.data.program_cd;
    input.classList.add('ag-child-checkbox');
    input.dataset['pgcd'] = params.data.program_cd;

    if (operatorValue) {
      input.checked = true;
    } else {
      input.checked = false;
    }

    return input;
  }
}
export default UsersDetailAuthForm;
