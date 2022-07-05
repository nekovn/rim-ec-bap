import CsvOutputForm from '@js/admin/page/forms/csvoutput/csv.output.form';
/**
 * CSV出力クラス
 */
class CsvOutputPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.CsvOutputForm = new CsvOutputForm(functionId);
  }
}
new CsvOutputPage('csv_output');
