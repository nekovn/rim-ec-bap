import AnimateService from '@js/service/animate';
import UsersSearchForm from './forms/users/users.search.form';
import UsersDetailForm from './forms/users/users.detail.form';
import UsersDetailAuthForm from './forms/users/users.detail.auth.form';

/**
 * ユーザーマスタページクラス
 */
class UsersPage {
  /**
   * コンストラクタ
   * @param {string} functionId 機能ID
   */
  constructor(functionId) {
    this.searchForm = new UsersSearchForm(functionId);
    this.detailForm = new UsersDetailForm(functionId);
    this.detailAuthForm = new UsersDetailAuthForm(functionId);
    AnimateService.showContent(`${functionId}-list-area`);
  }
}
new UsersPage('users');
