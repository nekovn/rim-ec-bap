import CategoriesForm from './forms/categories/categories.form.js';

/**
 * カテゴリー設定ページクラス
 */
class CaegoriesPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.categoriesForm = new CategoriesForm(functionId);
  }
}
new CaegoriesPage('categories');
