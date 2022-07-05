import {ImportBaseForm} from '@js/app/page/forms/import.base.form';

/**
 * 在庫取り込み画面クラス
 */
class ImportStocksForm extends ImportBaseForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      search: `/api/admin/import-stocks/getlogs`,
      upload: `/api/admin/import-stocks/upload`
    };

    super(functionId, requestUrls);
  }
}
export default ImportStocksForm;
