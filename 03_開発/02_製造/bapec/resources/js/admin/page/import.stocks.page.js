import ImportStocksForm from '@js/admin/page/forms/import/import.stocks.form.js';

/**
 * 在庫取込ページクラス
 */
class ImportShipsPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.form = new ImportStocksForm(functionId);
  }
}
new ImportShipsPage('importStocks');
