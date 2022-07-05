import {ImportBaseForm} from '@js/app/page/forms/import.base.form';

/**
 * 出荷実績取り込み画面クラス
 */
class ImportShipsAchieveForm extends ImportBaseForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      search: `/api/admin/import-ships/getlogs`,
      upload: `/api/admin/import-ships/upload`
    };
    super(functionId, requestUrls);
  }
}
export default ImportShipsAchieveForm;
