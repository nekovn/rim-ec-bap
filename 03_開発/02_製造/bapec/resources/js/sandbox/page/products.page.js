import AnimateService from '@js/service/animate';
import ProductsSearchForm from './forms/products/products.search.form';
import ProductsDetailForm from './forms/products/products.detail.form';

/**
 * 商品マスタページクラス
 */
class ProductsPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.searchForm = new ProductsSearchForm(functionId);
    this.detailForm = new ProductsDetailForm(functionId);
    AnimateService.showContent(`${functionId}-list-area`);
  }
}
new ProductsPage('products');
