import AnimateService from '@js/service/animate';
import CustomersSearchForm from './forms/customers/customers.search.form';
import CustomersDetailForm from './forms/customers/customers.detail.form';

/**
 * 顧客マスタページクラス
 */
class CustomersPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.searchForm = new CustomersSearchForm(functionId);
    this.detailForm = new CustomersDetailForm(functionId);
    AnimateService.showContent(`${functionId}-list-area`);
  }
}
new CustomersPage('customers');
