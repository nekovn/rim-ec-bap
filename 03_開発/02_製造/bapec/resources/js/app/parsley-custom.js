/**
 * 指定数値の倍数チェック定義
 */
window.Parsley.addValidator('multipleOf', {
  requirementType: 'integer',
  validateNumber: function (value, requirement) {
    return value % requirement === 0;
  },
  messages: {
    ja: '数量は%s単位でご指定ください。'
  }
});

/**
 * 3つのフィールドに別れた日付チェック定義
 */
window.Parsley.addValidator(
  'date',
  function (value, requirements) {
    var dayField = $('.' + requirements + '-day'),
      monthField = $('.' + requirements + '-month'),
      yearField = $('.' + requirements + '-year');

    dayField.parsley().reset();
    monthField.parsley().reset();
    yearField.parsley().reset();

    var day = parseInt(dayField.val()),
      month = parseInt(monthField.val()),
      year = parseInt(yearField.val());

    var date = new Date(year, month - 1, day);
    var checkMonth = date.getMonth() + 1;

    return month === checkMonth;
  },
  34
);

/**
 * 全角カタカナチェック
 */
window.parsley.addValidator('zenkana', {
  validateString: (value) => {
    if (!value) {
      return true;
    }
    const regex = new RegExp('^[ァ-ヶー　]*$');
    if (value.match(regex)) {
      return true;
    } else {
      return false;
    }
  },
  messages: {
    ja: fw.i18n.messages['E.input.zenkana']
  },
  priority: 32
});

/**
 * 開始日時 と 終了日時 の大小チェック
 */
window.parsley.addValidator('datetimerange', {
  validateString: (value, requirements) => {
    var startField = $('.' + requirements + '_start_datetime'),
      endField = $('.' + requirements + '_end_datetime');

    startField.parsley().reset();
    endField.parsley().reset();

    if (startField.val() !== '' && endField.val() !== '') {
      return Date.parse(startField.val()) < Date.parse(endField.val());
    }

    return true;
  },
  messages: {
    ja: fw.i18n.messages['E.datetime.range']
  },
  priority: 32
});
