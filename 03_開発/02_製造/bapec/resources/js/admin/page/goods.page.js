import AnimateService from '@js/service/animate';
import GoodsSearchForm from './forms/goods/goods.search.form';
import GoodsDetailForm from './forms/goods/goods.detail.form';

/**
 * 商品マスタページクラス
 */
class GoodsPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.searchForm = new GoodsSearchForm(functionId);
    this.detailForm = new GoodsDetailForm(functionId);
    AnimateService.showContent(`${functionId}-list-area`);
  }
}
new GoodsPage('goods');
