import ImportCategoriesForm from './forms/import/import.categories.form';

/**
 * カテゴリマスタ取込ページクラス
 */
class ImportCategoriesPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.form = new ImportCategoriesForm(functionId);
  }
}
new ImportCategoriesPage('importCategories');
