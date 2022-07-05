import GoodsImagesForm from '@js/admin/page/forms/goods/goods.images.form';

/**
 * 商品画像マスタページクラス
 */
class GoodsImagesPage {
  /**
   * コンストラクタ
   * @param functionId
   */
  constructor(functionId) {
    new GoodsImagesForm(functionId);
  }
}
new GoodsImagesPage('goods-images');
