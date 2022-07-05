/**
 * グリッドサービス
 *
 * @classdesc @js/service/grid.js
 *
 * <a href="https://www.ag-grid.com/documentation-main/documentation.php" target="_blank">参考：ag-grid documentation<a>
 */
class GridService {
  /**
   * htmlのid属性
   * @memberof GridService
   */
  #gridId;
  /**
   * 言語設定
   * @memberof GridService
   */
  #localeText;
  /**
   * ag-gridインスタンス
   * @memberof GridService
   */
  #instance = null;
  /**
   * a-grid選択行
   * @memberof GridService
   */
  #selectedRowNode = null;
  /**
   * コンストラクター
   *
   * @param {string} gridId htmlのid属性
   * @param {object} localeText 言語設定
   */
  constructor(gridId, localeText = AG_GRID_LOCALE_JA) {
    this.#gridId = gridId;
    this.#localeText = localeText;

    /**
     *  ヘッダーチェックボックスクリック
     *  class:ag-header-checkbox
     *  data-name:明細チェックボックス.name
     */
    $(document).on('click', '.ag-header-checkbox', (e) => {
      GridService.setHeaderCheckboxClick(e.currentTarget);
    });
    /** 明細チェックボックスクリック
     * class:ag-child-checkbox
     */
    $(document).on('click', '.ag-child-checkbox', (e) => {
      const elem = e.currentTarget;
      const name = elem.name;
      GridService.setHeaderCheck(name);
    });
  }
  /**
   * 明細checkboxのステータス変更
   *
   * @param elem:header checkbox
   * */
  static setHeaderCheckboxClick = (elem) => {
    elem.parentNode.classList.remove('ag-indeterminate');
    elem.parentNode.classList.remove('ag-checked');
    if (elem.checked) {
      elem.parentNode.classList.add('ag-checked');
    }

    const name = elem.dataset.name;
    const chks = document.querySelectorAll(
      'input[name="' + name + '"].ag-child-checkbox'
    );
    for (let i = 0; i < chks.length; i++) {
      chks[i].checked = elem.checked;
    }
  };
  /**
   * ヘッダcheckboxのステータス変更
   * child checkboxの状態に合わせてヘッダcheckboxの状態をセットする
   * @param name:child checkbox.name
   **/
  static setHeaderCheck = (name) => {
    const chks = document.querySelectorAll(
      '[data-name="' + name + '"].ag-header-checkbox'
    );
    if (chks.length == 0) {
      return true;
    }
    const headerChk = chks[0];
    const childChks = document.querySelectorAll(
      'input[name="' + name + '"].ag-child-checkbox'
    );
    let chkOn = 0,
      chkOff = 0;
    for (let i = 0; i < childChks.length; i++) {
      if (childChks[i].checked) {
        chkOn += 1;
      } else {
        chkOff += 1;
      }
    }
    headerChk.parentNode.classList.remove('ag-indeterminate');
    headerChk.parentNode.classList.remove('ag-checked');
    if (chkOn == childChks.length) {
      headerChk.checked = true;
      headerChk.parentNode.classList.add('ag-checked');
    } else if (chkOff == childChks.length) {
      headerChk.checked = false;
    } else {
      headerChk.parentNode.classList.add('ag-indeterminate');
    }
  };
  /**
   * グリッドを描画する。
   * 描画後、ページ切り替えで次ページのデータ取得。
   * @function
   * @memberof GridService
   * @param {array} gridColDefines GridColDefineクラスの配列
   * @param {array} rowData 行データ
   * @param {object} customGridOptions グリッドオプション
   * @param {array} dispCount 件数表示用 total,page,limit
   */
  render(
    gridColDefines = [],
    rowData = [],
    customGridOptions = {},
    dispCount = []
  ) {
    const agGridElement = document.getElementById(this.#gridId);
    if (this.#instance) {
      this.setRowData(rowData);
    } else {
      const gridOptions = this.createGridOptions(gridColDefines, rowData);
      Object.assign(gridOptions, customGridOptions);
      this.#instance = new agGrid.Grid(agGridElement, gridOptions);
      this.instance = this.#instance;
    }
    if ('total' in dispCount && 'page' in dispCount && 'limit' in dispCount) {
      this.setSearchResultGridCount(dispCount);
    } else {
      this.setGridCount();
    }
  }
  /**
   * ソートアイコンを非表示にする
   */
  clearSortIcon(sortItem = '', sortOrder = '') {
    const target = $('#' + this.#gridId);
    const showSortIcon =
      target.find('.ag-sort-ascending-icon').hasClass('ag-hidden') &&
      target.find('.ag-sort-descending-icon').hasClass('ag-hidden');
    if (showSortIcon) {
      $('.ag-sort-ascending-icon, .ag-sort-descending-icon').addClass(
        'ag-hidden'
      );
    }
    const allColumns = this.#instance.gridOptions.columnApi.getAllColumns();
    for (let i = 0; i < allColumns.length; i++) {
      if (allColumns[i].colDef.field == sortItem) {
        // allColumns[i].colDef.sort = sortOrder;
        $(
          `[col-id="${allColumns[i].colId}"] .ag-sort-${sortOrder}ending-icon`
        ).removeClass('ag-hidden');
        break;
      }
    }
  }
  /**
   * 数値formatter
   * valueFormatterに設定
   * @param  params
   */
  static formatNumber(params) {
    // nullであればnullで返す
    if (!params.value) {
      return params.value;
    }

    // 参考：https://www.ag-grid.com/javascript-grid-value-formatters/#value-formatter-example
    // 数値にカンマを入れる 例：1000 → 1,000
    return Math.floor(params.value)
      .toString()
      .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
  }
  /**
   * グリッドオプションの初期設定を定義する。
   * @function
   * @memberof GridService
   * @param {array} columnDefs 列定義
   * @param {array} rowData 行データ
   * @param {function} headerComponent ヘッダーコンポーネント
   * @return {object} グリッドオプション定義
   */
  createGridOptions(columnDefs, rowData, headerComponent = null) {
    const _this = this;

    if (!headerComponent) {
      headerComponent = this.getDefaultHeaderComponent();
    }
    return {
      // 列定義
      columnDefs: columnDefs,
      // 行データ
      rowData: rowData,
      // 言語設定
      localeText: this.#localeText,
      // 行の高さ
      rowHeight: 30,
      headerHeight: 30,
      // 行選択
      rowSelection: 'single',
      // マルチソート時のキー
      multiSortKey: 'ctrl',
      // ページネーション
      pagination: true,
      suppressPaginationPanel: true,
      suppressScrollOnNewData: true,
      // コンテンツの高さ自動
      domLayout: 'autoHeight',
      // 列ヘッダーコンポーネント登録
      components: {
        agColumnHeader: headerComponent
      },
      // 初期表示時
      onFirstDataRendered: (params) => {
        // 列幅をコンテンツ幅にフィットさせる
        // params.api.sizeColumnsToFit();
        // グリッド描画完了イベント発火
        $(`#${_this.#gridId}`).trigger('emit-grid-renderd', rowData);
      },
      // グリッドコンテンツのサイズが変わったとき、列幅の調整を行う。
      onGridSizeChanged: (params) => {
        // get the current grids width
        const gridWidth = document.getElementById(_this.#gridId).offsetWidth;

        // keep track of which columns to hide/show
        const columnsToShow = [];
        const columnsToHide = [];

        // iterate over all columns (visible or not) and work out
        // now many columns can fit (based on their minWidth)
        let totalColsWidth = 0;
        const allColumns = params.columnApi.getAllColumns();
        for (let i = 0; i < allColumns.length; i++) {
          const column = allColumns[i];
          totalColsWidth += column.getMinWidth();
          if (totalColsWidth > gridWidth) {
            columnsToHide.push(column.colId);
          } else {
            columnsToShow.push(column.colId);
          }
        }

        // show/hide columns based on current grid width
        params.columnApi.setColumnsVisible(columnsToShow, true);
        params.columnApi.setColumnsVisible(columnsToHide, false);

        // fill out any available space to ensure there are no gaps
        params.api.sizeColumnsToFit();
      },
      // セルがクリックされたとき
      onCellClicked: (params) => {
        this.#selectedRowNode = params.node;
      }
    };
  }
  /**
   * ヘッダーコンポーネントを生成する。
   * @function
   * @memberof GridService
   */
  getDefaultHeaderComponent() {
    const _this = this;
    function GridHeaderComponent() {}

    GridHeaderComponent.prototype.init = function (agParams) {
      this.agParams = agParams;

      const headerElement = document.createElement('div');
      this.eGui = headerElement;

      if (this.agParams.enableSorting) {
        headerElement.className = 'grid-header';
        headerElement.dataset.colId = this.agParams.column.colId;

        headerElement.addEventListener('click', (e) => {
          let $target = $(e.target);
          if (!$target.hasClass('grid-header')) {
            $target = $target.closest('.grid-header');
          }
          const colId = $target.data('col-id');
          if (!colId) {
            return;
          }
          const emitParam = {
            field: colId
          };
          const showSortIcon =
            $target.find('.ag-sort-ascending-icon').hasClass('ag-hidden') &&
            $target.find('.ag-sort-descending-icon').hasClass('ag-hidden');
          if (showSortIcon) {
            $('.ag-sort-ascending-icon, .ag-sort-descending-icon').addClass(
              'ag-hidden'
            );
          }
          if (
            !showSortIcon &&
            $target.find('.ag-sort-ascending-icon').hasClass('ag-hidden')
          ) {
            $target.find('.ag-sort-ascending-icon').removeClass('ag-hidden');
            $target.find('.ag-sort-descending-icon').addClass('ag-hidden');
            emitParam.order = 'asc';
          } else {
            $target.find('.ag-sort-ascending-icon').addClass('ag-hidden');
            $target.find('.ag-sort-descending-icon').removeClass('ag-hidden');
            emitParam.order = 'desc';
          }
          // ソートイベント発火
          $(`#${_this.#gridId}`).trigger('emit-grid-sort', emitParam);
        });

        //ソートアイコンデフォルト
        const sortorder = this.agParams.column.sort;
        //ソートはクリアする（ヘッダクリックでもsortが残ってしまうため、初期表示に使用したらクリアする）
        delete this.agParams.column.sort;
        this.eGui.innerHTML = `
          <div ref="eLabel" class="ag-header-cell-label" role="presentation" unselectable="on">
            <span ref="eText" class="ag-header-cell-text" unselectable="on">${
              this.agParams.displayName
            }</span>
            <span ref="eFilter" class="ag-header-icon ag-header-label-icon ag-filter-icon ag-hidden" aria-hidden="true">
              <span class="ag-icon ag-icon-filter" unselectable="on" role="presentation"></span>
            </span>
            <span ref="eSortOrder" class="ag-header-icon ag-header-label-icon ag-sort-order ag-hidden" aria-hidden="true"></span>
            <span ref="eSortAsc" class="ag-header-icon ag-header-label-icon ag-sort-ascending-icon ${
              sortorder == 'asc' ? '' : 'ag-hidden'
            }" aria-hidden="true">
              <span class="ag-icon ag-icon-asc" unselectable="on" role="presentation"></span>
            </span>
            <span ref="eSortDesc" class="ag-header-icon ag-header-label-icon ag-sort-descending-icon ${
              sortorder == 'desc' ? '' : 'ag-hidden'
            }" aria-hidden="true">
              <span class="ag-icon ag-icon-desc" unselectable="on" role="presentation"></span>
            </span>
            <span ref="eSortNone" class="ag-header-icon ag-header-label-icon ag-sort-none-icon ag-hidden" aria-hidden="true">
              <span class="ag-icon ag-icon-none" unselectable="on" role="presentation"></span>
            </span>
          </div>
        `;

        this.onSortAscRequestedListener = this.onSortRequested.bind(
          this,
          'asc'
        );
        this.onSortDescRequestedListener = this.onSortRequested.bind(
          this,
          'desc'
        );
      }
    };
    GridHeaderComponent.prototype.getGui = function () {
      return this.eGui;
    };
    GridHeaderComponent.prototype.onSortRequested = function (order, event) {
      this.agParams.setSort(order, event.shiftKey);
    };

    return GridHeaderComponent;
  }
  /**
   * ページャーの初期化を行う。
   * @function
   * @memberof GridService
   * @param {number} totalRows 総件数
   * @param {number} displayCount 表示件数
   * @param {number} pageNumber ページ番号
   */
  setupPagination(totalRows, displayCount, pageNumber = 1) {
    const selector = `#${this.#gridId}-pagination`;
    $(selector).twbsPagination('destroy');

    if (totalRows === 0) {
      return;
    }

    const totalPages = _.ceil(totalRows / displayCount);

    let options = {
      first: '<<',
      prev: '<',
      next: '>',
      last: '>>',
      initiateStartPageClick: false,
      totalPages: totalPages,
      pageSize: displayCount,
      startPage: Number(pageNumber),
      onPageClick: (e, page) => {
        $(selector).trigger('emit-page-change', page);
      }
    };
    $(selector).twbsPagination(options);

    // ag-gridへページャー情報設定
    this.#instance.gridOptions.api.paginationSetPageSize(displayCount);
    this.#instance.gridOptions.api.paginationGoToPage(pageNumber - 1);
  }
  /**
   * 現在のページ番号を返す。
   * @function
   * @memberof GridService
   * @return {number} ページ番号
   */
  getCurrentPageNumber() {
    return this.#instance.gridOptions.api.paginationGetCurrentPage();
  }
  /**
   * 列定義を返す。
   * @function
   * @memberof GridService
   * @param {object} paramter 列定義カスタム設定値
   * @return {object} 列定義
   */
  static getColDefine(params = {}) {
    const define = {
      // 列ヘッダー名
      headerName: '',
      // セルに表示する項目キー
      field: '',
      // // 列幅
      width: null,
      // // 列最小幅
      // minWidth: null,
      // // 列最大幅
      // maxWidth: null,
      // 列幅変更設定
      resizable: true,
      // 列ソート設定
      sortable: true
      // セル表示内容カスタマイズ
      // cellRenderer: null,
    };
    Object.assign(define, params);

    return define;
  }
  /**
   * フラグ=1のとき○を表示する
   * @function
   * @memberof GridService
   * @param {boolean} flg boolean項目の設定値
   * @param {string} value 表示ラベル（初期値：○）
   * @return {string} 表示ラベル
   */
  static convertFlgRenderer(flg, value = '○') {
    return flg ? value : null;
  }
  /**
   * 列ヘッダチェックボックス
   * @function
   * @memberof GridService
   * @param {string} name ヘッダーラベル
   * @return {string} レンダ―用html
   */
  static agGridCheckboxHeader(name) {
    return `
      <div class="ag-cell-label-container">
        <div ref="eLabel" class="ag-header-cell-label" role="presentation" unselectable="on">
          <div ref = "eWrapper" class="ag-wrapper ag-input-wrapper ag-checkbox-input-wrapper mr-2" role="presentation" unselectable="on">
            <input ref="eInput" class="ag-input-field-input ag-checkbox-input ag-header-checkbox" type="checkbox" tabindex="-1" data-name="${name}">
          </div>
          <span ref="eText" class="ag-header-cell-text" role="columnheader"></span>
          <span ref="eFilter" class="ag-header-icon ag-header-label-icon ag-filter-icon ag-hidden" aria-hidden="true">
            <span class="ag-icon ag-icon-filter" unselectable="on" role="presentation"></span>
          </span>
        </div>
      </div>
    `;
  }
  /**
   * グリッドの件数を表示する。グリッドの行を表示する。
   * @function
   * @memberof GridService
   */
  setGridCount() {
    const rowCount = this.#instance.gridOptions.api.getDisplayedRowCount();
    $(`.${this.#gridId}-total-count`).html(`全 ${rowCount} 件`);
  }
  /**
   * グリッドの件数を表示する。サーバー検索結果を表示する。
   * @function
   * @memberof GridService
   * @param {array} dispCount 件数表示用 total,page,limit
   */
  setSearchResultGridCount(dispCount) {
    let dispStr = '';
    if (dispCount.total == 0) {
      dispStr = '';
    } else {
      let endIdx = dispCount.page * dispCount.limit;
      const startIdx = (dispCount.page - 1) * dispCount.limit + 1;
      if (endIdx > dispCount.total) {
        endIdx = dispCount.total;
      }
      dispStr = `${startIdx} - ${endIdx} 件 | 全 ${dispCount.total} 件`;
    }

    $(`.${this.#gridId}-total-count`).html(dispStr);
  }

  /**
   * 一覧に行追加を行う。
   * @function
   * @memberof GridService
   * @param {array} rows 追加行
   */
  appendRows(rows = []) {
    if (_.isEmpty(rows)) {
      return;
    }
    this.#instance.gridOptions.api.updateRowData({add: rows});
    // 行データに追加
    let rowData = this.#instance.gridOptions.rowData;
    rowData.push(rows[0]);
    this.setRowData(rowData);

    this.setGridCount();
  }
  /**
   * 一覧の行を更新する。
   * @function
   * @memberof GridService
   * @param {array} rows 追加行
   */
  updateSelectedRow(row) {
    if (!this.#selectedRowNode) {
      return;
    }
    // 行データの選択行を更新
    const selectedRow = this.#instance.gridOptions.api.getFocusedCell();
    let rowData = this.#instance.gridOptions.rowData;
    rowData[selectedRow.rowIndex] = row;
    this.setRowData(rowData);
  }
  /**
   * 一覧の指定ノード行を削除する。
   * @function
   * @memberof GridService
   */
  deleteRow(nodeids = []) {
    if (_.isEmpty(nodeids)) {
      return;
    }

    nodeids.sort((a, b) => {
      return b - a;
    });

    let node = null;
    nodeids.forEach((nodeid) => {
      node = this.#instance.gridOptions.api.getRowNode(nodeid);

      this.#instance.gridOptions.api.applyTransaction({
        remove: [node.data]
      });

      // 行データから選択行を削除
      let rowData = this.#instance.gridOptions.rowData;
      rowData.splice(node.rowIndex, 1);
      this.setRowData(rowData);
    });

    this.setGridCount();
  }
  /**
   * 一覧の行を削除する。
   * @function
   * @memberof GridService
   */
  deleteSelectedRow() {
    if (!this.#selectedRowNode) {
      return;
    }
    this.#instance.gridOptions.api.applyTransaction({
      remove: [this.#selectedRowNode.data]
    });

    // 行データから選択行を削除
    const selectedRow = this.#instance.gridOptions.api.getFocusedCell();
    let rowData = this.#instance.gridOptions.rowData;
    rowData.splice(selectedRow.rowIndex, 1);
    this.setRowData(rowData);

    this.setGridCount();
  }
  /**
   *
   * @function
   * @memberof GridService
   */
  getAllRow() {
    return this.#instance.gridOptions.rowData;
  }
  /**
   * 指定行のデータを取得する。
   * @function
   * @memberof GridService
   * @param {number} index
   * @retrun {object} 行データ
   */
  getDisplayedRowAtIndex(index) {
    return this.#instance.gridOptions.api.getDisplayedRowAtIndex(index);
  }
  /**
   * 行のデータを設定する
   * @function
   * @memberof GridService
   * @param {array} rowData 行データ
   */
  setRowData(rowData) {
    this.#instance.gridOptions.rowData = rowData;
    this.#instance.gridOptions.api.setRowData(rowData);
  }
  /**
   * ag-gridインスタンスを削除する。
   * @function
   * @memberof GridService
   */
  destroy() {
    if (!this.#instance) {
      return;
    }
    this.#instance.gridOptions.api.destroy();
    this.#instance = null;
  }
}
export default GridService;
