/**
 * 日付に関する処理を定義する。
 *
 * @classdesc path: @js/service/date.calendar.js
 *
 * <pre>
 *   moment, flatpickerが必要<br>
 *   和暦入力が必要な場合---------
 *   和暦入力：R020101形式
 *   ・headタグに設定
 *     vendor/moment/locale/ja.js
 *   ・bindCalendar
 *     parseData,onCangeイベントのコメントを外す。
 *   ・FormExのDataCalender参照。
 * </pre>
 */
class DateService {
  /**
   * @memberof DateService
   * @desc 日付書式定数
   */
  static DATEFORMAT = {
    YYYYMMDD: 'YYYY-MM-DD',
    YYYYMM: 'YYYY-MM',
    YMD: 'Y-m-d',
    YM: 'Y-m',
    HHMMSS: 'HHmmss',
    HHMMSS_COLON: 'HH:mm:ss',
    HHMM_COLON: 'HH:mm',
    YYYYMMDDHHMMSS_SSS: 'YYYY-MM-DD HH:mm:ss.SSS',
    YYYYMMDD_SLASH_DDD: 'YYYY/MM/DD(ddd)',
    YYYYMMDD_SLASH_DDDD: 'YYYY/MM/DD(dddd)',
    YYYYMMDD_SLASH: 'YYYY/MM/DD',
    YYYYMM_SLASH: 'YYYY/MM',
    YYYYMMDD_DDDD: 'YYYY/MM/DD dddd',
    ISO: 'YYYY-MM-DDTHH:mm'
  };

  /**
   * 指定した年月を指定月の月末に変換する。
   * @function
   * @memberof DateService
   * @param {string} yyyymm 年月（yyyy/mm）
   * @return {date} 月末日
   */
  convertYM2LastDate(yyyymm) {
    return moment(yyyymm + '/01')
      .endOf('month')
      .format(DateService.DATEFORMAT.YYYYMMDD);
  }
  /**
   * 書式変換を行う。
   * @function
   * @memberof DateService
   * @param {string|date} sourceDate 書式変換対象日付
   * @param {string} format 変換書式(DateService.DATEFORMAT)
   * @return {date} 書式変換後の日付
   */
  static format(sourceDate, format) {
    if (!sourceDate) {
      return '';
    }
    if (!format) {
      format = DateService.DATEFORMAT.YYYYMMDD;
    }
    return moment(sourceDate).format(format);
  }
  /**
   * 時刻の書式変換を行う。
   * @function
   * @memberof DateService
   * @param {string} sourceTime 変換する時刻
   * @param {string} format 変換後の書式
   * @return {string} 変換後の書式
   */
  static formatTime(sourceTime, format) {
    if (!sourceTime) {
      return '';
    }
    if (!format) {
      format = DateService.DATEFORMAT.HHMMSS;
    }
    const sdate = DateService.format(
      new Date(),
      DateService.DATEFORMAT.YYYYMMDD
    );
    return moment(`${sdate} ${sourceTime}`).format(format);
  }
  /**
   * 指定した日付、時刻をISO書式に変換を行う。
   * @function
   * @memberof DateService
   * @param {string|date} 変換対象日付
   * @param {time} 変換対象時刻
   * @return {date} 書式変換後の日付
   */
  static formatISOFormatDateTime(date, time) {
    if (!time) {
      time = '00:00';
    }
    return `${date}T${time}`;
  }
  /**
   * 日付の加算を行う。
   * @function
   * @memberof DateService
   * @param {string|date} 加算対象日付
   * @param {number} addDays 加算日数
   * @param {string} 戻り値の書式
   * @return {date} 日付加算後の日付
   */
  static addDays(date, addDays, format) {
    if (!format) {
      format = DateService.DATEFORMAT.YYYYMMDD;
    }
    const d = moment(date);
    return d.add(addDays, 'days').format(format);
  }
  /**
   * 分の加算を行う
   * @function
   * @memberof DateService
   * @param {string} 加算対象時刻
   * @param {number} addMinutes 加算分
   * @param {string} 戻り値の書式
   * @return {date} 分加算後の時刻
   */
  static addMinutes(sTime, addMinutes, format) {
    if (!format) {
      format = 'HH:mm';
    }
    const sdate = DateService.format(new Date(), 'YYYY-MM-DD');
    const d = moment(`${sdate} ${sTime}`);
    return d.add(addMinutes, 'minutes').format(format);
  }
  /**
   * システム日付と指定日の日数差を計算する。
   * @function
   * @memberof DateService
   * @param {string|date} dateTo 計算対象日
   * @return {number} 差日数
   */
  static diffDate(dateTo) {
    const d = moment(dateTo).diff(moment().startOf('day'), 'days');
    return d;
  }
  /**
   * 年度を取得する。
   * @function
   * @memberof DateService
   * @param targetMonth 指定年月
   * @return {numer} 年
   * @desc 4月始まり固定
   */
  static getNendo(targetMonth) {
    const m = moment();
    const year = m.year();
    if (!targetMonth) {
      targetMonth = DateService.getMonth();
    }

    return targetMonth >= 4 ? year : year - 1;
  }
  /**
   * 現在日付の月を取得する。
   * @function
   * @memberof DateService
   * @return {number} 月
   */
  static getMonth() {
    return moment().month() + 1;
  }
  /**
   * 現在時刻を取得する。
   * @function
   * @memberof DateService
   * @param {string} format 日付書式
   * @return {daet} 現在時刻
   */
  static getTime(format) {
    if (!format) {
      format = 'YYYYMMDDhhmmss';
    }
    return moment().format(format);
  }
  /**
   * 日付ベースで過去日か判定する。
   * @function
   * @memberof DateService
   * @param {string|date} targetDate 対象日
   * @param {stirng|date} compareDate 比較対象日
   * @return {boolean} true: 過去, false: 当日or未来
   */
  static isPastDate(targetDate, compareDate) {
    return DateService.diffDate(compareDate, targetDate) <= 0;
  }
  /**
   * 時刻ベースで過去か判定する。
   * @function
   * @memberof DateService
   * @param {string|date} targetDate 対象日時
   * @param {stirng|date} compareDate 比較対象日時
   * @return {boolean} true: 過去, false: 当日or未来
   */
  static isAfter(targetDate, compareDate = null) {
    const target = moment(targetDate);
    let compare;
    if (compareDate) {
      compare = moment(compareDate);
    } else {
      compare = moment(new Date());
    }
    return compare.isAfter(target);
  }
  /**
   * 年齢を取得する。
   * @function
   * @memberof DateService
   * @param {string|date} sourceDate 基準日
   * @return {number} 年数
   */
  static calcAge(sourceDate) {
    if (!sourceDate) {
      return null;
    }
    const birthDay = moment(sourceDate);
    return moment().diff(birthDay, 'years');
  }
  /**
   * 日付の妥当性チェックを行う。
   * @function
   * @memberof DateService
   * @param {string|array} date チェック対象
   * @return {boolean} true: 正常, false: 異常
   */
  static isValid(date) {
    return moment(date, DateService.DATEFORMAT.YYYYMMDD).isValid();
  }
  /**
   * 和暦入力を西暦に変換する。
   * @function
   * @memberof DateService
   * @param {stirng|date} value 変換対象日
   * @return {date} 西暦
   */
  static convertToSeireki(value) {
    if (!value) return value;
    if (!value.length) return value;

    let erasja = moment().localeData().eras(); //ja configulation
    let erasN = value.substring(0, 1); //最初の１文字目
    let yy = value.substring(1, 3); //R010101
    let matchesStr = moment().localeData().erasAbbrRegex(true); //ja.eras.abbrを正規表現で表現した文字列

    let matches = erasN.match(matchesStr);

    if (matches) {
      for (let idx in erasja) {
        let era = erasja[idx];
        if (era.abbr == erasN) {
          let dir = era.since <= era.until ? +1 : -1;
          let sinceyear = moment(era.since).format('YYYY');
          let yyyy =
            parseInt(sinceyear) + (parseInt(yy) - parseInt(era.offset)) * dir;
          //(yyyy - sinceyear) * dir
          let date = yyyy + value.substring(3);
          let sincedate = moment(era.since).format('YYYYMMDD');
          if (date < sincedate) {
            return value;
          }
          if (era.until != Infinity) {
            let untildate = moment(era.until).format('YYYYMMDD');
            if (date > untildate) {
              return value;
            }
          }
          return date;
        }
      }
    }

    return value;
  }
}
export default DateService;
