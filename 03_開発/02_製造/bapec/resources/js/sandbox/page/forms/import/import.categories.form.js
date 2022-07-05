import {ImportBaseForm} from '@js/app/page/forms/import.base.form';

/**
 * カテゴリマスタ取り込み画面クラス
 */
class ImportCategoriesForm extends ImportBaseForm {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    const requestUrls = {
      search: '/api/admin/import/categories/getlogs',
      upload: '/api/admin/import/categories/upload'
    };
    super(functionId, requestUrls);

    /**
     * ダウンロードボタン押下
     */
    $(`#${functionId}_download_btn`).on('click', (e) => {
      // リンクを作成しダウンロード
      const download_target = document.getElementById(`btn-download-exec`);
      $(`#btn-download-exec`).empty();
      const link = document.createElement('a');
      link.href = '/api/admin/import/categories/getcsv';
      download_target.appendChild(link);
      link.click();
    });
  }
}
export default ImportCategoriesForm;
