import SagawaOutputForm from '@js/admin/page/forms/sagawaoutput/sagawa.output.form';
/**
 * 佐川連携ファイル出力クラス
 */
class SagawaOutputPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.SagawaOutputForm = new SagawaOutputForm(functionId);
  }
}
new SagawaOutputPage('sagawa-output');
