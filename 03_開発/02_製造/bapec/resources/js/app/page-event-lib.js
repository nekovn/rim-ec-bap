/*eslint no-unused-vars: off*/
import AddrService from '@js/service/addr';
import DateService from '@js/service/date.calendar';
import StringService from '@js/service/string';

/***********************/
/** 画面共通イベント定義 */
/***********************/
const fwJson = StringService.jsonStringfy(fw);
window.fw = StringService.jsonParse(fwJson);

fw.lib = {
  /**
   * オリジンを取得する。
   */
  getOrigin: () => location.origin,
  /**
   * ゼロパディング
   */
  zeroPadding: (e) => {
    if (!e.value) {
      return;
    }
    const maxlength = $(e).attr('maxlength');
    if (!maxlength) {
      return;
    }
    $(e).val(_.padStart(e.value, maxlength, '0'));
  },
  /**
   * 郵便番号書式変換
   */
  formatPostCode: (e) => {
    if (!$(e).data('format-hyphen') || e.value.length !== 7) {
      return;
    }
    const value = e.value;
    const postCode = `${value.slice(0, 3)}-${value.slice(3, value.length)}`;
    $(e).val(postCode);
  },
  /**
   * 郵便番号から住所を補完する。
   */
  zipToAddrAutocomplete: (e) => AddrService.bind(e, AddrService.url.ADDR),
  /**
   * 電話番号書式変換
   */
  formatTel: (e) => {
    if (!$(e).data('format-hyphen')) {
      return;
    }
    const formattedNumber = new libphonenumber.AsYouType('JP').input(e.value);
    $(e).val(formattedNumber);
  },
  /**
   * 生年月日計算処理
   */
  calcAge: (e) => {
    const value = e.value;
    if (!value || !$(e).data('age')) {
      return;
    }
    if (!DateService.isValid(value)) {
      return;
    }
    const age = DateService.calcAge(value);
    const ageSelector = $(e).data('age');
    const $age = $(ageSelector);
    if (
      $age.prop('tagName') === 'INPUT' ||
      $age.prop('tagName') === 'TEXTAREA'
    ) {
      $age.val(age);
    } else {
      $age.text(age);
    }
  },
  /**
   * class属性に「input-date」が付与された要素に、カレンダーをバインドする。
   * @function
   * @param {string} selector flatpicker適用セレクター
   * @param {object} settings flatpickr設定
   * @return {object} flatpickrObject
   */
  bindCalendar: (selector = '.input-date', settings) => {
    if ($(selector).length === 0) {
      return;
    }
    let config = {
      allowInput: true,
      locale: fw.config.locale,
      dateFormat: 'Y-m-d',
      disableMobile: true,
      wrap: fw.config.page.supportParts.date.datepicker['use-calendar-icon'],
      clickOpens: true,
      onChange: function (selectedDates, dateStr, instance) {
        if (fw.config.page.supportParts.date.datepicker['use-calendar-icon']) {
          $(instance.element).find('input').val(dateStr).parsley().validate();
          $(instance.element).find('input').trigger('change');
        }
      }
    };
    if (settings) {
      config = Object.assign(config, settings);
    }

    let flatpickrObject;
    if (fw.config.page.supportParts.date.datepicker['use-calendar-icon']) {
      flatpickrObject = $('.datepicker-wrapper').flatpickr(config);
    } else {
      flatpickrObject = $(selector).flatpickr(config);
    }
    // Enterキー押下で、カレンダーを閉じる
    $(selector).on('keydown', (e) => {
      if (
        e.keyCode === fw.KeyEvent.DOM_VK_RETURN ||
        e.keyCode === fw.KeyEvent.DOM_VK_TAB
      ) {
        flatpickrObject.setDate(this.value);
        flatpickrObject.close();
      } else if (
        e.keyCode === fw.KeyEvent.DOM_VK_BACK_SPACE ||
        e.keyCode === fw.KeyEvent.DOM_VK_DELETE
      ) {
        e.target.value = '';
      }
    });
  },
  /**
   * 年月の入力設定
   * @param {string}} selector 年月項目のセレクター
   * @param {object} settings flatpickr設定
   */
  bindMonthCalendar: (selector = '.input-month', settings) => {
    if ($(selector).length === 0) {
      return;
    }
    let config = {
      allowInput: false,
      locale: fw.config.locale,
      plugins: [
        new monthSelectPlugin({
          shorthand: true, //defaults to false
          dateFormat: 'Y/m', //defaults to "F Y"
          altFormat: 'F Y', //defaults to "F Y"
          theme: 'light' // defaults to "light"
        })
      ],
      onChange: function (selectedDates, dateStr, instance) {
        if (fw.config.page.supportParts.date.monthpicker['use-calendar-icon']) {
          $(instance.element).find('input').val(dateStr);
        }
      }
    };
    if (settings) {
      config = Object.assign(config, settings);
    }
    let flatpickrObject;
    if (fw.config.page.supportParts.date.monthpicker['use-calendar-icon']) {
      flatpickrObject = $('.monthpicker-wrapper').flatpickr(config);
    } else {
      flatpickrObject = $(selector).flatpickr(config);
    }
    // Enterキー押下で、カレンダーを閉じる
    $(selector).on('keydown', (e) => {
      if (
        e.keyCode == fw.KeyEvent.DOM_VK_RETURN ||
        e.keyCode == fw.KeyEvent.DOM_VK_TAB
      ) {
        flatpickrObject.setDate(this.value);
        flatpickrObject.close();
      }
    });
  },
  /**
   * 時分の入力設定
   * @function
   * @param {string} 適用するセレクター
   * @param {string|date} 日付初期値
   */
  bindTimePicker(selector = '.input-time', defaultDate) {
    if ($(selector).length === 0) {
      return;
    }
    let config = {
      enableTime: true,
      noCalendar: true,
      dateFormat: 'H:i',
      time_24hr: true,
      onValueUpdate(selectedDates, dateStr, instance) {
        let target;
        if (fw.config.page.supportParts.date.timepicker['use-calendar-icon']) {
          if ($(instance.element).is('input')) {
            target = $(instance.element);
          } else {
            target = $(instance.element).find('input');
          }
          target.val(dateStr);
        } else {
          target = $(instance.element);
        }
        if (target.hasClass('tooltipstered')) {
          target.tooltipster('destroy');
        }
        if (typeof target.parsley().reset() == 'function') {
          target.parsley().reset();
        }
        target.removeAttr('title');
      }
    };
    if (defaultDate) {
      config['defaultDate'] = defaultDate;
    }
    if (fw.config.page.supportParts.date.timepicker['use-calendar-icon']) {
      $('.timepicker-wrapper').flatpickr(config);
    } else {
      $(selector).flatpickr(config);
    }

    $(selector).flatpickr(config);
  },
  /**
   * 年月日・時分の入力設定
   * @function
   * @param {string} 適用するセレクター
   * @param {string|date} 日付初期値
   */
  bindDateTimePicker(selector = '.input-date-time', defaultDate) {
    if ($(selector).length === 0) {
      return;
    }
    let config = {
      enableTime: true,
      allowInput: true,
      locale: fw.config.locale,
      dateFormat: 'Y-m-d H:i',
      disableMobile: true,
      wrap: fw.config.page.supportParts.date.datetimepicker['use-calendar-icon']
    };
    if (defaultDate) {
      config['defaultDate'] = defaultDate;
    }
    if (fw.config.page.supportParts.date.datetimepicker['use-calendar-icon']) {
      $('.datetime-wrapper').flatpickr(config);
    } else {
      $(selector).flatpickr(config);
    }
  },
  /**
   * 日付の開始、終了をバインドする。
   * @function
   * @param {string} RangeCalendarをFromを適用するセレクター
   * @param {object} settings flatpickr設定
   */
  bindRangeCalendar(selector = '.input-range-from-date', settings) {
    if ($(selector).length === 0) {
      return;
    }
    $(selector).each((index, element) => {
      const toElement = $(element).data('to-id');
      let config = {
        allowInput: false,
        locale: fw.config.locale,
        plugins: [new rangePlugin({input: toElement})],
        onChange: function (selectedDates, dateStr, instance) {
          if (
            fw.config.page.supportParts.date.datepicker['use-calendar-icon']
          ) {
            $(instance.element).find('input').val(dateStr);
          }
        }
      };
      if (settings) {
        config = Object.assign(config, settings);
      }
      let flatpickrObject;
      if (fw.config.page.supportParts.date.datepicker['use-calendar-icon']) {
        flatpickrObject = $('.fp-range').flatpickr(config);
      } else {
        flatpickrObject = $(element).flatpickr(config);
      }
      // Enterキー押下で、カレンダーを閉じる
      $(selector).on('keydown', (e) => {
        if (
          e.keyCode == fw.KeyEvent.DOM_VK_RETURN ||
          e.keyCode == fw.KeyEvent.DOM_VK_TAB
        ) {
          flatpickrObject.setDate(this.value);
          flatpickrObject.close();
        }
      });
    });
  }
};

/**
 * clearableを適用
 */
const tog = (v) => (v ? 'add' : 'remove');
$(document)
  .on('input', '.clearable', (e) => {
    e.target.classList[tog(e.target.value)]('x');
  })
  .on('mousemove', '.x', (e) => {
    e.target.classList[
      tog(
        e.target.offsetWidth - 18 <
          e.clientX - e.target.getBoundingClientRect().left
      )
    ]('onX');
  })
  .on('click', '.onX', (e) => {
    e.target.classList.remove('x', 'onX');
    e.target.value = '';
  });

/**
 * カレンダーバインド
 */
if (window.moment) {
  moment.locale(fw.config.locale);
}

if (fw.config.page.supportParts.date.autoBind) {
  fw.lib.bindCalendar();
  fw.lib.bindMonthCalendar();
  fw.lib.bindTimePicker();
  fw.lib.bindDateTimePicker();
  fw.lib.bindRangeCalendar();
}
