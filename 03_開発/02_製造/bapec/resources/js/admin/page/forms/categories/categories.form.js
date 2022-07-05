import GridService from '@js/service/grid';
import Messages from '@js/app/messages';
import {ModalMessage, Toaster} from '@js/service/notifications';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import {SimpleCrudSearchForm} from '@js/app/page/forms/simple.crud.search.form';
import GoodsSelectDialogForm from '@js/admin/page/forms/goods/goods.select.dialog.form';
import StringService from '@js/service/string';
import Dialog from '@js/service/dialog';

class CategoriesForm extends SimpleCrudSearchForm {
  constructor(functionId) {
    const requestUrls = {
      update: '/api/admin/categories', //categories
      store: '/api/admin/categories/store', //category_goods
      search: '/api/admin/categories/search', //category_goods
      delete: '/api/admin/categories/:id' //category_goods.delete
    };

    super(functionId, requestUrls);
    this.defaultPagesSettings.sortItem = 'code';

    this.defaultGridData = null; //カテゴリ商品１ページ目データ保持
    this.gridColDefine.useDelete = false;
    this.gridColDefine.useEdit = false;

    //----- 商品ダイアログ
    this.dialogGoodsId = 'dialog-goods-select';
    this.goodsDialogForm = new GoodsSelectDialogForm(this.dialogGoodsId, true);

    Dialog.settingDialog(`#${this.dialogGoodsId}-area`, '商品検索', {
      width: 1000,
      height: 500,
      resizable: true,
      position: {my: 'center top', at: 'center top+10', of: window}
    });
    // 商品一覧dialog表示
    $(`#${functionId}-btn-goods-search`).on('click', () => {
      if (!$('input[name="code"]').val()) {
        return false;
      }
      $(`#${this.dialogGoodsId}-search-result`).addClass('d-none');

      $(`#${this.dialogGoodsId}-area input[name="search-category_code"]`).val(
        $('input[name="code"]').val()
      ); //カテゴリコード設定
      $(`#${this.dialogGoodsId}-area`).simpleDialog('open');
    });
    const goodsDialog = document.getElementById(`${this.dialogGoodsId}`);
    //商品選択時
    goodsDialog.addEventListener('selected', (e) => {
      const selectGoods = e.detail.ids;
      if (selectGoods.length == 0) {
        return false;
      }
      const storeGoods = () => {
        $(`#${this.dialogGoodsId}-area`).simpleDialog('close');

        //商品を追加する
        const params = {
          ids: selectGoods,
          form: this.createSearchParameter(),
          code: $('input[name="code"]').val(),
          page: {
            count: $(`#${this.functionId}-display-count`).val(),
            page: 1,
            sortItem: this.defaultPagesSettings.sortItem,
            sortOrder: this.defaultPagesSettings.sortOrder
          }
        };
        this.doRegistGoods(params);
      };
      //追加確認メッセージ
      ModalMessage.showConfirm(
        '<p>' + fw.i18n.messages['C.addgoods.confirm'] + '</p>',
        storeGoods
      );
    });
    //----------------------
    /**
     * Tree ルートカテゴリ追加
     */
    $(`#${this.functionId}-btn-topcategory-create`).on('click', (e) => {
      this.nodeRootCreate();
    });

    /**
     * Tree カテゴリ追加
     */
    $(`#${this.functionId}-btn-category-create`).on('click', (e) => {
      this.nodeCreate();
    });

    /**
     * Tree 反映
     */
    $(`#${this.functionId}-btn-category-reflect`).on('click', (e) => {
      this.nodeReflect();
    });

    /**
     * Tree カテゴリ削除
     */
    $(`#${this.functionId}-btn-category-delete`).on('click', (e) => {
      this.nodeDelete();
    });

    /**
     * 更新ボタン押下
     */
    $(`#${functionId}-btn-update`).on('click', (e) => {
      //チェック
      let categories = [];
      const ref = $('#tree').jstree(true);
      const aryJson = ref.get_json(); //ツリーの並びの通りに値を返す
      for (let i = 0; i < aryJson.length; i++) {
        let json = aryJson[i];
        let hierarchy = 1;
        let selnode = ref.get_node(json.id);
        let ret = this.getNodeParamter(ref, selnode, categories, hierarchy);
        if (!ret) {
          Toaster.showAlertToaster(
            Messages.getMessage('E.nosetting', {
              field: 'カテゴリコード、又はカテゴリ名'
            })
          );
          return false;
        }
      }
      // const fncUpdate = (categories) => {
      //   this.doUpdate(categories);
      // };
      ModalMessage.showModifyConfirm(
        ModalMessage.MODIFY_MODE.UPD,
        null,
        this.doUpdate.bind(null, categories)
      );
    });
    /*
     *  カテゴリ商品一覧部削除ボタンクリック
     */
    $(`#${this.functionId}-grid`).on('click', '.btn-goods-delete', (e) => {
      const callback = () => {
        const params = {
          id: e.currentTarget.querySelector('i').dataset.id,
          code: $('input[name="code"]').val(),
          form: this.createSearchParameter(),
          page: {
            count: $(`#${this.functionId}-display-count`).val(),
            page: 1,
            sortItem: this.defaultPagesSettings.sortItem,
            sortOrder: this.defaultPagesSettings.sortOrder
          }
        };
        this.doDeleteGoods(params);
      };

      ModalMessage.showModifyConfirm(
        ModalMessage.MODIFY_MODE.DEL,
        null,
        callback
      );
    });

    /**
     * 画面遷移時
     */
    super.addEventBeforeUnload();

    //--- 検索
    this.doSearch();
  }

  /**
   * 一覧部の再描画を行う。override
   * @function
   * @memberOf SimpleCrudSearchForm
   * @param {object} res 検索結果
   */
  renderGrid(res) {
    if (res.type == 'grid') {
      //grid表示
      super.renderGrid(res.content);
    } else {
      //tree作成
      this.renderTree(res.content.data);
      if (res.data) {
        let ref = $('#tree').jstree(true);
        ref.select_node(res.data[0].id);
      }
      this.defaultGridData = res.content.goods;
    }
  }
  /**
   * カテゴリツリー表示
   * @param {}} $data
   */
  renderTree($data) {
    $('#tree').jstree('destroy');
    $('#tree')
      .jstree({
        core: {
          data: $data,
          // check_callback: function (
          //   operation,
          //   node,
          //   node_parent,
          //   node_position,
          //   more
          // ) {
          //   return operation === 'create_node' ? true : false; //作成のみOK
          // },
          check_callback: true,
          force_text: true,
          multiple: false
        },
        types: {
          default: {
            icon: 'fas fa-leaf'
          },
          '#': {
            icon: 'fas fa-tree'
          },
          new: {
            icon: 'fas fa-bolt text-warning'
          }
        },
        plugins: ['dnd', 'types'] //dnd:drop and drop
      })
      .on('ready.jstree', function (e, data) {
        data.instance.open_all();
        data.instance.select_node(data.instance.get_json(0)[0].id);
      })
      .on('changed.jstree', (e, data) => {
        if (data.selected.length == 0) {
          return false;
        }
        if (!data.node) {
          return false;
        }
        let values = {
          code: data.selected[0],
          name: data.node.original.name
            ? data.node.original.name
            : data.node.text
        };
        this.bindValue(values);
        //新規の時はコード入力可能にする
        if (data.node.type == 'new') {
          $('input[name="code"]').attr('disabled', false);
        } else {
          $('input[name="code"]').attr('disabled', true);
        }

        //ページ設定をデフォルトにする
        super.setPageSettingsDefault();
        if (this.gridInstance) {
          this.gridInstance.clearSortIcon(
            this.defaultPagesSettings.sortItem,
            this.defaultPagesSettings.sortOrder
          );
        }
        //1ページ目のデータを表示する
        if (!this.defaultGridData[values.code]) {
          this.defaultGridData[values.code] = {data: [], total: 0};
        }
        $(`${this.formSelector} input[name="search-code"]`).val(values.code);

        super.renderGrid(this.defaultGridData[values.code]);
      });
  }
  /**
   * ツリールート作成
   */
  nodeRootCreate() {
    let ref = $('#tree').jstree(true);
    let node = ref.create_node(null, {type: 'new', node_parent: ''}, 'last');
    // ref.edit(node, );
    ref.deselect_all(true);
    ref.select_node(node);
    this.changeFormObserver();
  }

  /**
   * ツリーノード作成
   */
  nodeCreate() {
    let ref = $('#tree').jstree(true),
      sel = ref.get_selected();
    if (!sel.length) {
      return false;
    }
    sel = sel[0];
    sel = ref.create_node(sel, {type: 'new', node_parent: sel[0].id});
    if (sel) {
      //   ref.edit(sel);
      // ref.set_text(sel, `[${sel.id}]：${sel.text}`);
      ref.deselect_all(true);
      ref.select_node(sel);
      this.changeFormObserver();
      document.getElementsByName('code')[0].focus();
    }
  }
  /**
   * 反映
   */
  nodeReflect() {
    let ref = $('#tree').jstree(true);
    let sel = ref.get_selected();
    if (!sel.length) {
      return false;
    }
    let selnode = ref.get_node(sel);
    const code = $('input[name="code"]').val();
    const name = $('input[name="name"]').val();
    if (!code || !name) {
      return false;
    }
    //同じコードがあればエラー
    if (selnode.type == 'new' && sel != code && code in ref._model.data) {
      Toaster.showAlertToaster(
        Messages.getMessage('E.samevalue', {field: 'カテゴリコード'})
      );
      return false;
    }

    ref.set_id(selnode, code);
    ref.set_text(selnode, `[${code}]：${name}`);

    // let selnode = ref.get_node(sel);
    selnode.original['id'] = code;
    selnode.original['name'] = name;
    // ref.edit(sel);
    this.changeFormObserver();
  }
  /**
   * ツリーノードRename
   */
  //   nodeRename() {
  // let ref = $('#tree').jstree(true),
  //   sel = ref.get_selected();
  // if (!sel.length) {
  //   return false;
  // }
  // sel = sel[0];
  // ref.edit(sel);
  //   }
  /**
   * ツリーノード削除
   */
  nodeDelete() {
    let ref = $('#tree').jstree(true),
      sel = ref.get_selected();
    if (!sel.length) {
      return false;
    }
    let selnode = ref.get_node(sel);
    if (
      ref.is_parent(selnode) ||
      this.agGridInstance.gridOptions.rowData.length > 0
    ) {
      Toaster.showAlertToaster(fw.i18n.messages['E.delete.parentcategory']);
      return false;
    }
    ref.delete_node(sel);
    this.changeFormObserver();
  }
  /**
   * 関連商品の登録処理を行う。
   * @function
   */
  doRegistGoods = (params) => {
    const xhrParam = new XhrParam(this.requestUrls.store, params);
    const successhandler = (res) => {
      this.defaultGridData[params['code']] = res;

      super.setPageSettingsDefault();
      super.renderGrid(res); //商品テーブル表示更新
    };

    XHRCrudMethod.store(xhrParam, successhandler);
  };
  /**
   * 関連商品の削除処理を行う。
   * @function
   */
  doDeleteGoods = (params) => {
    const url = StringService.bindParameter(this.requestUrls.delete, params);
    const xhrParam = new XhrParam(url, params);
    const successHandler = (res) => {
      this.defaultGridData[params['code']] = res;

      super.setPageSettingsDefault();
      super.renderGrid(res); //商品テーブル表示更新
    };
    XHRCrudMethod.delete(xhrParam, successHandler);
  };
  /**
   * 更新処理を行う。
   * @function
   * @memberOf SimpleCrudDetailForm
   */
  doUpdate = (categories) => {
    //sequenceセット
    categories.forEach((element, index) => {
      element['sequence'] = index + 1;
    });
    let requestParameter = categories;

    const xhrParam = new XhrParam(this.requestUrls.update, requestParameter);
    const successHandler = (res) => {
      this.resetFormObserver();
      this.renderGrid(res);
    };

    XHRCrudMethod.update(xhrParam, successHandler);
  };
  /**
   * 更新用パラメータをセットする
   * @param {*} ref
   * @param {*} selnode
   * @param {*} datas
   * @param {*} hierarchy
   */
  getNodeParamter(ref, selnode, datas, hierarchy) {
    if (!selnode.original.id || !selnode.original.name) {
      return false; //値を設定していない
    }
    //親たち取得
    let parents = selnode.parents.reverse(); //逆順
    parents.shift(); //先頭はルートなので削除
    parents.push(selnode.original.id); //自分自身追加
    let data = {
      table: 'categories',
      code: selnode.original.id,
      name: selnode.original.name,
      hierarchy: hierarchy,
      // 'sequence': sort,
      path: parents.join('~') + '~',
      updated_at: selnode.original.updated_at
    };
    datas.push(data);

    if (ref.is_parent(selnode)) {
      hierarchy = hierarchy + 1;
      for (let i = 0; i < selnode.children.length; i++) {
        let child = selnode.children[i];
        let childNode = ref.get_node(child);
        let ret = this.getNodeParamter(ref, childNode, datas, hierarchy);
        if (!ret) {
          return false;
        }
      }
    }
    return true;
  }
  /**
   * 一覧の列定義を取得する。
   * @function
   * @memberOf UsersSearchForm
   * @return {array} 列定義体
   */
  getGridColDefine() {
    let colDefines = [];
    colDefines.push(
      GridService.getColDefine({
        headerName: '商品コード',
        field: 'code',
        width: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '商品名',
        field: 'name'
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: '規格',
        field: 'volume',
        width: 150,
        maxWidth: 150
      })
    );
    colDefines.push(
      GridService.getColDefine({
        headerName: 'メーカー',
        field: 'maker_name'
      })
    );
    colDefines.push(
      GridService.getColDefine({
        minWidth: 60,
        maxWidth: 60,
        sortable: false,
        cellStyle: {cursor: 'pointer'},
        cellClass: ['btn-goods-delete'],
        cellRenderer: (params) =>
          `<i class="far fa-trash-alt  text-danger" data-id="${params.data.id}" title="削除"></i>`
      })
    );
    return colDefines;
  }
}
export default CategoriesForm;
