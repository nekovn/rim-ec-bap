import BaseForm from '@js/app/page/forms/base.form';

/**
 * 佐川連携ファイル出力
 */
class SagawaOutputForm extends BaseForm {
  // 機能ID
  #functionId;

  // リクエストURL
  #requestUrls = {
    downloadGoods: '/api/admin/sagawa-output/download-goods',
    downloadShips: '/api/admin/sagawa-output/download-ships'
  };

  /**
   * コンストラクタ
   * @param {string} functionId 機能ID functionIdは、各bladeのfunctionIdを設定する（csv-output)
   * @param isDialog true:ダイアログ表示
   */
  constructor(functionId) {
    super(`#form-${functionId}`);
    this.#functionId = functionId;
    this.setUpFormObserver();

    // 商品ダウンロードボタンクリック
    $(`#${functionId}-btn-download-goods`).on('click', (e) => {
      $('#download-goods-message').hide();
      this.download(this.#requestUrls.downloadGoods);
    });

    // 商品ダウンロードボタンクリック
    $(`#${functionId}-btn-download-ships`).on('click', (e) => {
      $('#download-ships-message').hide();
      this.download(this.#requestUrls.downloadShips);
    });
  }

  /**
   * ダウンロード
   */
  download = (url) => {
    const download_target = document.getElementById(
      `${this.#functionId}-btn-download-exec`
    );
    $(`#${this.#functionId}-btn-download-exec`).empty();
    const link = document.createElement('a');
    link.href = url;
    download_target.appendChild(link);
    link.click();
  };
}
export default SagawaOutputForm;
