import GridService from '@js/service/grid';
import {Toaster} from '@js/service/notifications';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import BaseForm from '@js/app/page/forms/base.form.js';

/**
 * 商品検索画面クラス
 */
class ProductsDialogForm extends BaseForm {
  /**
   * サムネイル用キャッシュハッシュ
   */
  #now;

  // 機能ID
  #functionId;
  // リクエストURL
  #requestUrls;
  // 一覧部の定義
  #gridColDefines;

  #pagesSettings = {
    page: 1,
    sortItem: 'id',
    sortOrder: 'asc'
  };

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    super(`#form-${functionId}-searchcondition`);
    this.#functionId = functionId;
    this.gridInstance = null;
    this.#requestUrls = {
      search: '/api/admin/products/search'
    };

    this.#now = new Date().getTime();

    // grid列定義
    let colDefines = [];
    colDefines.push(
      GridService.getColDefine({
        width: 85,
        cellClass: [
          'pb-0',
          'pt-0',
          'grid-btn',
          'align-middle',
          'text-center',
          'dialog_select_btn'
        ],
        cellRenderer: (params) =>
          `<button type="button" class="btn btn-outline-dark btn-sm pt-0 pb-0 select_btn" 
                data-nodeid="${params.rowIndex}">選択</button>`
      })
    );

    colDefines.push(
      GridService.getColDefine({
        headerName: '画像',
        field: 'image',
        width: 90,
        cellClass: ['align-middle', 'text-center'],
        cellRenderer: (params) => {
          if (params.value == null) {
            return '<div class="no_image">NO IMAGE</div>';
          }
          let $file_path =
            params.value.replace('public', '/storage') + '?' + this.#now;
          return `<img src="${$file_path}">`;
        }
      })
    );

    colDefines.push(
      GridService.getColDefine({
        headerName: '商品名',
        field: 'name',
        cellRenderer: (params) => {
          return `<div class="break_column">${params.value}</div>`;
        }
      })
    );

    colDefines.push(
      GridService.getColDefine({
        headerName: '商品コード',
        field: 'code',
        width: 100
      })
    );

    colDefines.push(
      GridService.getColDefine({
        headerName: 'JANコード',
        field: 'jan_code',
        width: 130
      })
    );

    colDefines.push(
      GridService.getColDefine({
        headerName: 'カテゴリ',
        field: 'category',
        width: 160,
        cellClass: ['align-middle'],
        cellRenderer: (params) => {
          if (
            (params.data.class1_name != null) &
            (params.data.class2_name != null)
          ) {
            return params.data.class1_name + `<br>` + params.data.class2_name;
          } else {
            return params.data.class1_name + params.data.class2_name;
          }
        }
      })
    );

    colDefines.push(
      GridService.getColDefine({
        headerName: 'メーカー',
        field: 'brand_name',
        width: 160
      })
    );

    colDefines.push(
      GridService.getColDefine({
        headerName: '定番区分',
        field: 'class_div',
        width: 120,
        cellClass: ['align-middle'],
        cellRenderer: (params) => {
          if (fw.define.ClassDivDefine.STANDARD == params.data.class_div) {
            return '定番品';
          } else if (
            fw.define.ClassDivDefine.SEASONAL == params.data.class_div
          ) {
            return '季節品商品';
          } else if (
            fw.define.ClassDivDefine.BACK_ORDER == params.data.class_div
          ) {
            return '取り寄せ品';
          } else {
            return '';
          }
        }
      })
    );

    this.#gridColDefines = colDefines;

    /**
     * 検索ボタン押下
     */
    $(`#${this.#functionId}-btn-search`).on('click', (e) => {
      this.doSearch();
    });

    /**
     * クリアボタン押下
     */
    $(`#${this.#functionId}-btn-clear`).on('click', (e) => {
      super.resetForm();
    });

    /**
     * 選択クリック
     */
    $(document).on('click', '.select_btn', (e) => {
      const nodeid = e.currentTarget.dataset.nodeid;
      const rowNode = this.gridInstance.getDisplayedRowAtIndex(nodeid);
      this.doSelect(rowNode.data);
    });

    // 小カテゴリ：イベント
    $('#search-class1_code').change(function () {
      var class2 = window.class2;
      $('#search-class2_code > option').remove();
      $('#search-class2_code').append(
        $('<option>').html('選択してください').val('')
      );
      $("#search-class2_code option[value='']").prop('selected', true);
      for (let i = 0; i < class2[$('#search-class1_code').val()].length; i++) {
        $('#search-class2_code').append(
          $('<option>')
            .html(class2[$('#search-class1_code').val()][i]['text'])
            .val(class2[$('#search-class1_code').val()][i]['value'])
        );
      }
    });
  }

  /**
   * 検索処理を行う。
   * @function
   * @memberOf SimpleCrudSearchForm
   */
  doSearch() {
    const requestParameter = {
      form: this.formValueToJson('search-'),
      page: {
        count: $(`#${this.#functionId}-display-count`).val(),
        page: this.#pagesSettings.page,
        sortItem: this.#pagesSettings.sortItem,
        sortOrder: this.#pagesSettings.sortOrder
      }
    };

    const xhrParam = new XhrParam(this.#requestUrls.search, requestParameter);
    const successHandler = (res) => {
      if (res.message) {
        Toaster.showWarningToaster(res.message);
      }
      this.renderGrid(res);
    };
    XHRCrudMethod.get(xhrParam, successHandler);
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
        this.doSelect(row.data);
      }
    };
    Object.assign(gridOptions, this.getCustomGridOptions());
    this.gridInstance.render(this.#gridColDefines, res.data, gridOptions);
  }

  getCustomGridOptions() {
    return {
      rowHeight: 64
    };
  }

  /**
   * 選択処理
   */
  doSelect(row) {
    const event = new CustomEvent('emit-dialog-row-selected', {
      detail: row
    });
    document.getElementById(this.#functionId).dispatchEvent(event);
    $(`#${this.#functionId}-area`).dialog('close');
  }

  /**
   * 検索前処理
   * @return {bool} 処理結果
   */
  preSearch() {
    return !this.hasError();
  }
}
export default ProductsDialogForm;
