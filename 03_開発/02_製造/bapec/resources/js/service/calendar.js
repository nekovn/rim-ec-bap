import {Calendar} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import jaLocale from '@fullcalendar/core/locales/ja';
import DateService from '@js/service/date.calendar';
import {XHRCrudMethod, XhrParam} from '@js/service/xhr';

/**
 * カレンダークラス
 */
class CalendarBase {
  /**
   * イベントオブジェクトを作成する。
   * @functin
   * @memberof Calender
   * @param {number} id eventを一意に示すキー
   * @param {string} title eventタイトル
   * @param {string|date} start 開始日
   * @param {string|date} end 終了時間
   * @param {*} extras
   *
   * <a href="https://fullcalendar.io/docs/event-object" target="_blank">Event Object Document</a>
   */
  static createEventObject(id, title, start, end, extras = {}) {
    const eventObject = {
      id: id,
      title: title,
      start: start,
      end: end
    };
    Object.assign(eventObject, extras);
    return eventObject;
  }
}
/**
 * 月・カレンダーサービス
 *
 * @classdesc path: @js/service/calendar.js
 *
 * <a href="https://fullcalendar.io/docs" target="_blank">参考：fullcalendar documentation<a>
 */
class MonthCalendar extends CalendarBase {
  /**
   * htmlのid属性
   * @memberof MonthCalendar
   */
  #calendarId;
  /**
   * カレンダーイベント取得先URL
   */
  #requestUrl;
  /**
   * fullcalendarインスタンス
   * @memberof MonthCalendar
   */
  #instance = null;
  /**
   * カレンダーイベントキャッシュデータ
   * @memberof MonthCalendar
   */
  #cache = {};
  /**
   * コンストラクター
   *
   * @param {string} calendarId htmlのid属性
   * @param {object} localeText 言語設定
   */
  constructor(calendarId, requestUrl, settings = {}) {
    super();
    if (!settings.locale) {
      settings.locale = fw.config.locale;
    }
    const monthCalendarConfig = fw.config.page.supportParts.calendar.month;
    const calendarOptions = {
      locale: jaLocale,
      plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
      initialView: monthCalendarConfig.initialView,
      navLinks: monthCalendarConfig.navLinks,
      firstDay: monthCalendarConfig.firstDay,
      headerToolbar: monthCalendarConfig.headerToolbar,
      buttonText: monthCalendarConfig.buttonText,
      displayEventTime: monthCalendarConfig.displayEventTime,
      allDaySlot: monthCalendarConfig.allDaySlot,
      businessHours: monthCalendarConfig.businessHours,
      height: window.innerHeight - 100,
      eventDisplay: fw.config.page.supportParts.calendar.eventDisplay,
      dayCellContent: (e) => {
        e.dayNumberText = e.dayNumberText.replace('日', '');
      },
      windowResize: () => {
        this.#instance.setOption('height', window.innerHeight - 100);
      },
      /** 日がクリックされた処理 */
      dateClick: (info) => {
        $(`#${this.#calendarId}`).trigger(
          'emit-month-calendar-day-clicked',
          info.dateStr
        );
      },
      /** イベントがクリックされた処理 */
      eventClick: (info) => {
        $(`#${this.#calendarId}`).trigger(
          'emit-month-calendar-event-clicked',
          info.event
        );
      }
    };
    Object.assign(calendarOptions, settings);

    this.#calendarId = calendarId;
    const calendarEl = document.getElementById(this.#calendarId);
    this.#instance = new Calendar(calendarEl, calendarOptions);
    this.#instance.render();
    /** 前へボタンがクリックされた時 */
    $(`#${this.#calendarId} .fc-prev-button`).on('click', (e) => {
      $(`#${this.#calendarId}`).trigger(
        'emit-month-calendar-event-prev-clicked',
        DateService.format(this.#instance.getDate())
      );
    });
    /** 次へボタンがクリックされた時 */
    $(`#${this.#calendarId} .fc-next-button`).on('click', (e) => {
      $(`#${this.#calendarId}`).trigger(
        'emit-month-calendar-event-next-clicked',
        DateService.format(this.#instance.getDate())
      );
    });

    this.#requestUrl = requestUrl;
    this.fetch(new Date());
  }
  /**
   * パラメータの設定に基いてリクエストを要求し、結果をカレンダーに反映する。
   * @function
   * @memberof MonthCalendar
   * @param {string} targetDate 取得対象月
   * @param {boolean} force true: キャッシュ設定が有効でもリクエストを要求する。
   */
  fetch(targetDate, force = false) {
    if (!this.#requestUrl) {
      return;
    }

    const yyyyMM = DateService.format(
      targetDate,
      DateService.DATEFORMAT.YYYYMM
    );

    if (!force && fw.config.page.supportParts.calendar.month.useCache) {
      if (this.#cache[yyyyMM]) {
        const events = _.map(this.#cache[yyyyMM], (event) => event);
        this.rerender(events);
        return;
      }
    }

    const successHandler = (res) => {
      this.rerender(res);
    };
    const xhrParam = new XhrParam(this.#requestUrl, {yyyyMM: yyyyMM});
    XHRCrudMethod.get(xhrParam, successHandler);
  }
  /**
   * イベント数を取得する。
   * @function
   * @memberof MonthCalendar
   */
  getEventCount() {
    return this.#instance.getEvents().length;
  }
  /**
   * イベントオブジェクトをキャッシュに登録する。
   * @param {object} event イベントオブジェクト
   */
  addCache(event) {
    if (!fw.config.page.supportParts.calendar.month.useCache) {
      return;
    }

    const yyyymm = DateService.format(
      event.start,
      DateService.DATEFORMAT.YYYYMM
    );
    if (!this.#cache[yyyymm]) {
      this.#cache[yyyymm] = {};
    }

    let chacedEvents = this.#cache[yyyymm];
    chacedEvents[event.id] = event;
  }
  deleteCache(start, id) {
    if (!fw.config.page.supportParts.calendar.month.useCache) {
      return;
    }

    const yyyymm = DateService.format(start, DateService.DATEFORMAT.YYYYMM);
    delete this.#cache[yyyymm][id];
  }
  /**
   * イベントを追加する。
   * @function
   * @memberof MonthCalendar
   * @param {object} eventObject イベントオブジェクト
   */
  addEvent(event) {
    this.#instance.addEvent(event);
    this.addCache(event);
  }
  /**
   * イベントを複数追加する。
   * @function
   * @memberof MonthCalendar
   * @param {array} events イベントオブジェクトの配列
   */
  addEvents(events = []) {
    events.forEach((event) => this.addEvent(event));
  }
  /**
   * イベントを更新する。
   * @function
   * @memberof MonthCalendar
   * @param {object} sourceEvent イベントオブジェクト
   */
  updateEvent(sourceEvent) {
    const event = this.#instance.getEventById(sourceEvent.id);
    Object.keys(sourceEvent).forEach((key) => {
      const value = sourceEvent[key];
      switch (key) {
        case 'start':
          event.setStart(value);
          break;
        case 'end':
          event.setEnd(value);
          break;
        case 'dates':
          event.setDates(value);
          break;
        case 'allDay':
          event.setAllDay(value);
          break;
        default:
          event.setProp(key, value);
          break;
      }
    });
    this.addCache(sourceEvent);
  }
  /**
   * イベントを削除する。
   * @function
   * @memberof MonthCalendar
   * @param {number} id イベントID
   */
  deleteEvent(id) {
    const event = this.#instance.getEventById(id);
    event.remove();
    this.deleteCache(event.start, event.id);
    console.log(this.cache());
  }
  /**
   * カレンダーイベントを再描画する。
   * @function
   * @memberof MonthCalendar
   * @param {array} events イベントオブジェクトの配列
   */
  rerender(events = []) {
    this.#instance.getEvents().forEach((event) => {
      event.remove();
    });
    this.addEvents(events);
  }
  /**
   * カレンダーインスタンスを返す。
   * @function
   * @memberof MonthCalendar
   * @return {object} fullcalendarインスタンス
   */
  get instance() {
    return this.#instance;
  }
  /**
   * カレンダーの要素を示すセレクターを返す。
   * @function
   * @memberof MonthCalendar
   * @return {string} セレクター
   */
  get calendarId() {
    return this.#calendarId;
  }
  cache() {
    return this.#cache;
  }
}
export {MonthCalendar};
