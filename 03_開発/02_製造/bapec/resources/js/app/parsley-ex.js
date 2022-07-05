import {Toaster} from '@js/service/notifications';
import StringService from '@js/service/string';

/*************************************************/
/* Parsely Tooltip                               */
/*************************************************/
/**
 * 入力チェック該当なし（入力正常）
 */
window.Parsley.on('field:success', (fieldInstance) => {
  if (fieldInstance.$element.hasClass('tooltipstered')) {
    fieldInstance.$element.tooltipster('destroy');
    Toaster.resetAll();
  }
  fieldInstance.$element.removeClass('is-invalid');
});
/**
 * 入力チェック該当有（入力異常）
 */
window.Parsley.on('field:error', (fieldInstance) => {
  var messages = ParsleyUI.getErrorsMessages(fieldInstance);
  if (fw.config.page.validation['error-notify'] === 'tooltip') {
    if (fieldInstance.$element.hasClass('tooltipstered')) {
      fieldInstance.$element.tooltipster('destroy');
    }
    fieldInstance.$element.attr('title', messages.join('<br />'));
    fieldInstance.$element.tooltipster({
      contentAsHTML: true,
      side: 'bottom',
      theme: 'tooltipster-borderless'
    });
    fieldInstance.$element.tooltipster('enable');
    fieldInstance.$element.addClass('is-invalid');

    Toaster.showAlertToaster(fw.i18n.messages['E.input.confirm']);
  }
});
/**
 * 入力チェック該当なし（入力正常）
 */
// window.Parsley.on("field:success", (fieldInstance) => {
//   if (fieldInstance.$element.hasClass("tooltipstered")) {
//     fieldInstance.$element.tooltipster("destroy");
//   }
// });
// /**
//  * 入力チェック該当有（入力異常）
//  */
// window.Parsley.on("field:error", (fieldInstance) => {
//   var messages = fieldInstance.getErrorsMessages();
//   if (fieldInstance.$element.hasClass("tooltipstered")) {
//     fieldInstance.$element.tooltipster("destroy");
//   }
//   fieldInstance.$element.attr("title", messages.join("<br />"));
//   fieldInstance.$element.tooltipster({
//     contentAsHTML: true,
//     side: "bottom",
//     theme: "tooltipster-borderless"
//   });
//   fieldInstance.$element.tooltipster("enable");
// });

/*************************************************/
/* Parsely Original Validation                   */
/*************************************************/
/**
 * 電話番号入力チェック定義
 */
window.Parsley.addValidator('phoneNumber', {
  requirementType: 'string',
  validateString: (value) => {
    const formattedNumber = new libphonenumber.AsYouType('JP').input(value);
    return libphonenumber.isValidNumber(formattedNumber, 'JP');
  },
  priority: 32
});
/**
 * 日付(YYYYMMDD)チェック定義
 */
window.parsley.addValidator('dateText', {
  requirementType: 'string',
  validateString: (value) => {
    if (!value) {
      return true;
    }
    const $isValid = moment(value).isValid();
    if ($isValid) {
      return true;
    }
    return $.Deferred().reject(fw.i18n.messages['E.input.date']);
  },
  priority: 32
});
/**
 * 入力値が等しいチェック定義
 */
window.parsley.addValidator('equalTo', {
  validateString: function validateString(value, refOrValue, target) {
    if (!value) {
      return true;
    }

    const $reference = $(refOrValue);
    if ($reference.length) {
      if (value !== $reference.val()) {
        return $.Deferred().reject(
          StringService.bindParameter(
            fw.i18n.messages['E.input.equalto.compare'],
            {value: target.$element.data('compare-name')}
          )
        );
      }
    } else {
      if (value !== refOrValue) {
        return $.Deferred().reject(
          StringService.bindParameter(
            fw.i18n.messages['E.input.equalto.string'],
            {value: target.$element.data('parsley-equal-to')}
          )
        );
      }
    }
    return true;
  },
  priority: 32
});
// gt, gte, lt, lte, notequalto extra validators
var parseRequirement = function (requirement) {
  if (isNaN(+requirement)) return parseFloat($(requirement).val());
  else return +requirement;
};
/**
 * 入力値の超過チェック定義
 */
window.Parsley.addValidator('gt', {
  validateString: function (value, requirement, target) {
    const bool = parseFloat(value) > parseRequirement(requirement);
    if (!bool) {
      const value = $(requirement).data('compare-name')
        ? $(requirement).data('compare-name')
        : target.$element.data('parsley-gt');

      return $.Deferred().reject(
        StringService.bindParameter(fw.i18n.messages['E.input.greater.than'], {
          value: value
        })
      );
    }
    return true;
  },
  priority: 32
});
/**
 * 入力値の以上チェック定義
 */
window.Parsley.addValidator('gte', {
  validateString: function (value, requirement, target) {
    const bool = parseFloat(value) >= parseRequirement(requirement);
    if (!bool) {
      const value = $(requirement).data('compare-name')
        ? $(requirement).data('compare-name')
        : target.$element.data('parsley-gte');

      return $.Deferred().reject(
        StringService.bindParameter(
          fw.i18n.messages['E.input.greater.than.eq'],
          {value: value}
        )
      );
    }
    return true;
  },
  priority: 32
});
/**
 * 入力値の以下チェック定義
 */
window.Parsley.addValidator('lte', {
  validateString: function (value, requirement, target) {
    const bool = parseFloat(value) <= parseRequirement(requirement);
    if (!bool) {
      const value = $(requirement).data('compare-name')
        ? $(requirement).data('compare-name')
        : target.$element.data('parsley-lte');

      return $.Deferred().reject(
        StringService.bindParameter(fw.i18n.messages['E.input.less.than.eq'], {
          value: value
        })
      );
    }
    return true;
  },
  priority: 32
});
/**
 * 入力値の未満チェック定義
 */
window.Parsley.addValidator('lt', {
  validateString: function (value, requirement, target) {
    const bool = parseFloat(value) < parseRequirement(requirement);
    if (!bool) {
      const value = $(requirement).data('compare-name')
        ? $(requirement).data('compare-name')
        : target.$element.data('parsley-lt');

      return $.Deferred().reject(
        StringService.bindParameter(fw.i18n.messages['E.input.less.than'], {
          value: value
        })
      );
    }
    return true;
  },
  priority: 32
});
/**
 * 日付入力値の以上チェック定義
 */
window.Parsley.addValidator('gteDate', {
  validateString: function (value, requirement, target) {
    const bool = !$(requirement).val() || value >= $(requirement).val();
    if (!bool) {
      const value = $(requirement).data('compare-name')
        ? $(requirement).data('compare-name')
        : target.$element.data('parsley-gte-Date');

      return $.Deferred().reject(
        StringService.bindParameter(
          fw.i18n.messages['E.input.date.greater.than.eq'],
          {
            value: value
          }
        )
      );
    }
    return true;
  },
  priority: 32
});
/**
 * 日付入力値の以下チェック定義
 */
window.Parsley.addValidator('lteDate', {
  validateString: function (value, requirement, target) {
    const bool = !$(requirement).val() || value <= $(requirement).val();
    if (!bool) {
      const value = $(requirement).data('compare-name')
        ? $(requirement).data('compare-name')
        : target.$element.data('parsley-lte-Date');

      return $.Deferred().reject(
        StringService.bindParameter(
          fw.i18n.messages['E.input.date.less.than.eq'],
          {
            value: value
          }
        )
      );
    }
    return true;
  },
  priority: 32
});

/**
 * 以上(数値変換)
 */
// window.Parsley.addValidator('gte', {
//   requirementType: 'string',
//   validateString: (value, requirement) => {
//     if (!$(requirement).val() || value * 1 >= $(requirement).val() * 1) {
//       return true;
//     }
//     const name = $(requirement).data('compare-name');
//     return $.Deferred().reject(BcMessage.getMessage('E_BCCM00_0016', [name]));
//   },
//   priority: 32
// });
// /**
//  * 以下(数値変換)
//  */
// window.Parsley.addValidator('lte', {
//   requirementType: 'string',
//   validateString: (value, requirement) => {
//     if (!$(requirement).val() || value * 1 <= $(requirement).val() * 1) {
//       return true;
//     }
//     const name = $(requirement).data('compare-name');
//     return $.Deferred().reject(BcMessage.getMessage('E_BCCM00_0015', [name]));
//   },
//   priority: 32
// });
// /**
//  * より大きい
//  */
// window.Parsley.addValidator('gtDate', {
//   requirementType: 'string',
//   validateString: (value, requirement) => {
//     if (!$(requirement).val() || value > $(requirement).val()) {
//       return true;
//     }
//     const name = $(requirement).data('compare-name');
//     return $.Deferred().reject(BcMessage.getMessage('E_BCCM00_0007', [name]));
//   },
//   priority: 32
// });
// /**
//  * 以上
//  */
// window.Parsley.addValidator('gteDate', {
//   requirementType: 'string',
//   validateString: (value, requirement) => {
//     if (!$(requirement).val() || value >= $(requirement).val()) {
//       return true;
//     }
//     const name = $(requirement).data('compare-name');
//     return $.Deferred().reject(BcMessage.getMessage('E_BCCM00_0007', [name]));
//   },
//   priority: 32
// });
// /**
//  * 未満
//  */
// window.Parsley.addValidator('ltDate', {
//   requirementType: 'string',
//   validateString: (value, requirement) => {
//     if (!$(requirement).val() || value < $(requirement).val()) {
//       return true;
//     }
//     const name = $(requirement).data('compare-name');
//     return $.Deferred().reject(BcMessage.getMessage('E_BCCM00_0019', [name]));
//   },
//   priority: 32
// });
// /**
//  * 以下
//  */
// window.Parsley.addValidator('lteDate', {
//   requirementType: 'string',
//   validateString: (value, requirement) => {
//     if (!$(requirement).val() || value <= $(requirement).val()) {
//       return true;
//     }
//     const name = $(requirement).data('compare-name');
//     return $.Deferred().reject(BcMessage.getMessage('E_BCCM00_0019', [name]));
//   },
//   priority: 32
// });
// /**
//  * 時刻専用：より大きい
//  */
// window.Parsley.addValidator('gtTime', {
//   requirementType: 'string',
//   validateString: (value, requirement) => {
//     if (!$(requirement).val() || value > $(requirement).val()) {
//       return true;
//     }
//     const name = $(requirement).data('compare-name');
//     return $.Deferred().reject(BcMessage.getMessage('E_BCCM00_0025', [name]));
//   },
//   priority: 32
// });
// /**
//  * 時刻専用：未満
//  */
// window.Parsley.addValidator('ltTime', {
//   requirementType: 'string',
//   validateString: (value, requirement) => {
//     if (!$(requirement).val() || value < $(requirement).val()) {
//       return true;
//     }
//     const name = $(requirement).data('compare-name');
//     return $.Deferred().reject(BcMessage.getMessage('E_BCCM00_0024', [name]));
//   },
//   priority: 32
// });
// /**
//  * 有効時間チェック
//  */
// window.Parsley.addValidator('validTime', {
//   requirementType: 'string',
//   validateString: (value, requirement) => {
//     const match = /^([01][0-9]|2[0-3]):[0-5][0-9]$/.test(value);
//     if (match) {
//       return true;
//     }
//     return $.Deferred().reject(BcMessage.getMessage('E_BCCM00_0008'));
//   },
//   priority: 32
// });
