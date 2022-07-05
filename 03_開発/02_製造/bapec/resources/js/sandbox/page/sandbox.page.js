import AnimateService from '@js/service/animate';
import SandboxSearchForm from './forms/sandbox/sandbox.search.form';
// import SandboxDetailForm from './forms/sandbox/sandbox.detail.form';

class SandboxPage {
  constructor(functionId) {
    this.searchForm = new SandboxSearchForm(functionId);
    // this.detailForm = new SandboxDetailForm(functionId);
    AnimateService.showContent(`${functionId}-list-area`);
  }
}
new SandboxPage('sandbox');
