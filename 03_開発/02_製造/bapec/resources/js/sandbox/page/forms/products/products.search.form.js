import GridService from '@js/service/grid';
import {SimpleCrudSearchForm} from '@js/app/page/forms/simple.crud.search.form';

/**
 * 商品検索画面クラス
 */
class ProductsSearchForm extends SimpleCrudSearchForm {
  /**
   * サムネイル用キャッシュハッシュ
   */
  #now;

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      search: '/api/admin/products/search',
      delete: '/api/admin/products/:id'
    };
    super(functionId, requestUrls, 'code');

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

    // 新規作成ボタン削除
    $(`#${functionId}-btn-create`).remove();

    this.#now = new Date().getTime();

    // ダッシュボードからの遷移だったら未編集のリストを表示する
    if (window.from_dashboard) {
      let unedited = $('#search_unedited');
      unedited[0].checked = true;
      this.doSearch();
    }
  }

  getCustomGridOptions() {
    return {
      rowHeight: 64
    };
  }

  /**
   * 一覧の列定義を取得する。
   * @function
   * @memberOf ProductsSearchForm
   * @return {array} 列定義体
   */
  getGridColDefine() {
    let colDefines = [];

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
        field: 'brand_name'
      })
    );

    colDefines.push(
      GridService.getColDefine({
        headerName: '画面更新日',
        field: 'edited_at',
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

    colDefines.push(
      GridService.getColDefine({
        headerName: '未編集',
        field: 'unedited',
        width: 80,
        cellRenderer: (params) => {
          return params.value == fw.define.UneditedDefine.UNEDITED
            ? '未編集'
            : '';
        }
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
export default ProductsSearchForm;
