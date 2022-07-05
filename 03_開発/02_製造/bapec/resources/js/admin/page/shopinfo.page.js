import ShopinfoForm from '@js/admin/page/forms/shop/shopinfo.form';

/**
 * ショップ基本情報 ページクラス
 */
class ShopinfoPage {
  /**
   * コンストラクタ
   * @param functionId
   */
  constructor(functionId) {
    new ShopinfoForm(functionId);
  }
}
new ShopinfoPage('shopinfo');
