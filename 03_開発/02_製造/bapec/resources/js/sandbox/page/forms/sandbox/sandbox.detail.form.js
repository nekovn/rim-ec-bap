import {SimpleCrudDetailForm} from '@js/app/page/forms/simple.crud.detail.form';

class SandboxDetailForm extends SimpleCrudDetailForm {
  constructor(functionId) {
    const requestUrls = {
      store: '/api/admin/sandbox/store',
      edit: '/api/admin/sandbox/:id/edit',
      update: '/api/admin/sandbox/:id',
      delete: '/api/admin/sandbox/:id'
    };

    super(functionId, requestUrls);
  }
}
export default SandboxDetailForm;
