import GridService from '@js/service/grid';
import {SimpleCrudSearchForm} from '@js/app/page/forms/simple.crud.search.form';

/**
 * 商品検索画面クラス
 */
class GoodsSearchForm extends SimpleCrudSearchForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      search: '/api/admin/goods/search',
      delete: '/api/admin/goods/:id'
    };

    super(functionId, requestUrls);
    this.defaultPagesSettings.sortItem = 'name';

    // 大カテゴリ：イベント
    $('#search-class1_code').change(function () {
      var class2 = window.class2;
      $('#search-class2_code > option').remove();
      $('#search-class2_code').append($('<option>').html('未選択').val(''));
      $("#search-class2_code option[value='']").prop('selected', true);
      if (class2[$('#search-class1_code').val()] !== undefined) {
        for (
          let i = 0;
          i < class2[$('#search-class1_code').val()].length;
          i++
        ) {
          $('#search-class2_code').append(
            $('<option>')
              .html(class2[$('#search-class1_code').val()][i]['text'])
              .val(class2[$('#search-class1_code').val()][i]['value'])
          );
        }
      }
    });

    //サムネイル設定ボタンクリック
    $(document).on('click', '.thumbnail-link', (e) => {
      const id = e.currentTarget.dataset.id;
      window.location.href = `/admin/goods/${id}/thumbnail`;
    });

    // サムネイルからの戻りの時は検索をかける
    if (window.isBack === 1) {
      this.setSearchPage();
      this.doSearch();
    }
  }

  getCustomGridOptions() {
    return {
      rowHeight: 52
    };
  }

  /**
   * 一覧の列定義を取得する。
   * @function
   * @memberOf GoodsSearchForm
   * @return {array} 列定義体
   */
  getGridColDefine() {
    let colDefines = [];
    colDefines.push(
      GridService.getColDefine({
        headerName: '商品画像',
        width: 40,
        cellClass: ['align-middle', 'text-center'],
        cellRenderer: (params) => {
          const path = params.data.image_url;
          if (path !== undefined && path !== null && path !== '') {
            return `<img src="${path}" alt="X" height="50" class="load-chk">`;
          } else {
            return '<div class="no_image">NO IMAGE</div>';
          }
        }
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '商品コード',
        field: 'code',
        sizeColumnsToFit: true,
        width: 150
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
        headerName: 'メーカー',
        field: 'maker_name',
        width: 120
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '公開状況',
        width: 100,
        field: 'published'
        // cellRenderer: (params) => {
        //   if (params.data.is_published === 0) {
        //     return '非公開';
        //   } else if (params.data.is_published === 1) {
        //     return '公開';
        //   } else {
        //     return '';
        //   }
        // }
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '販売ステータス',
        field: 'sale_status_nm',
        width: 100
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: 'サムネイル',
        minWidth: 120,
        width: 120,
        maxWidth: 120,
        sortable: false,
        cellClass: ['align-middle', 'grid-btn'],
        cellRenderer: (params) =>
          `<button type="button" class="thumbnail-link btn btn-outline-dark btn-sm pt-0 pb-0 auth-link w-100"
            data-id="${params.data.id}">サムネイル</button>`
      })
    );

    return colDefines;
  }

  /**
   * 検索前処理
   * @return {bool} 処理結果
   */
  preSearch() {
    return !this.hasError();
  }
}

export default GoodsSearchForm;
