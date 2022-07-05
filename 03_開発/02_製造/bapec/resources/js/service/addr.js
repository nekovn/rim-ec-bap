import {XHRCrudMethod, XhrParam} from '@js/service/xhr';

/**
 * 住所関連サービス
 *
 * @classdesc path: @js/service/addr.js
 */
class AddrService {
  /**
   * 検索対象
   *
   * @memberOf AddrService
   */
  static #url = {
    ADDR: 'addr', // 郵便番号から住所
    ZIP: 'zip' // 住所から郵便番号
  };
  /**
   * 郵便番号、住所を検索するAutoCompleteをバインドする。
   *
   * @function
   * @memberof AddrService
   * @param selector {string} 要素のセレクター
   */
  static bind(selector, key) {
    if ($(selector).hasClass('ui-autocomplete-input')) {
      return;
    }
    $(selector).autocomplete({
      source: (request, reseponse) => {
        const zipCode = encodeURIComponent(request.term);
        const xhrParam = new XhrParam(`/api/zip/search/${key}/${zipCode}`);
        xhrParam.spinnerParam.show = false;
        XHRCrudMethod.get(xhrParam, (res) => {
          $(selector).data('zips', JSON.stringify(res));
          reseponse(res);
        });
      },
      response: (e, ui) => {
        if (ui.content.length === 1) {
          $(selector)
            .data('ui-autocomplete')
            ._trigger('select', 'autocompleteselect', {
              item: ui.content[0]
            });
        }
      },
      autoFocus: true,
      delay: 100,
      minLength: 7,
      select: (e, ui) => {
        if (ui.item) {
          const zips = JSON.parse($(selector).data('zips'));
          const zip = _.find(zips, (zip) => zip.label == ui.item.label);

          const prefSelector = $(selector).data('selector-pref');
          const citySelector = $(selector).data('selector-city');
          const townSelector = $(selector).data('selector-town');

          if (prefSelector) {
            $(prefSelector).val(zip.local_cd.substr(0, 2));
          }
          if (citySelector) {
            $(citySelector).val(zip.municipality_name);
          }
          if (townSelector) {
            const townName =
              zip.town_name != '以下に掲載がない場合' ? zip.town_name : '';
            $(townSelector).val(townName);
          }
        }
      }
    });
    //日本語入力をスタートしたら無効化
    $(selector).on('compositionstart', () =>
      $(selector).autocomplete('disable')
    );
    //日本語入力が確定したら有効化
    $(selector).on('compositionend', () =>
      $(selector).autocomplete('enable').autocomplete('search')
    );

    // 個別にトリガーが設定されている場合
    if (typeof $(selector).data('trigger') !== 'undefined') {
      const trigger = $(selector).data('trigger');
      $(document).on('click', trigger, (e) => {
        $(selector).autocomplete('enable').autocomplete('search');
        $(selector).autocomplete('disable');
      });
    }
  }
  /**
   * townnameが有効であるか判定
   * townname == '以下に掲載がない場合'だったら有効でない
   *
   * @function
   * @memberof AddrService
   * @param {string} townName 郵便番号検索で取得したtownName
   */
  static validTownName(townName) {
    return townName != '以下に掲載がない場合';
  }
  /**
   * 数値を郵便番号形式（###-####）に変換
   *
   * @function
   * @memberof AddrService
   * @param {string} source 書式変換対象文字列
   */
  static formatZip(source) {
    if (!source) {
      return '';
    }
    return source.replace(/(\d{3})(\d{4})/, '$1-$2');
  }
  /**
   * リクエストURLを返す。
   *
   * @function
   * @memberof AddrService
   */
  static get url() {
    return this.#url;
  }
}
export default AddrService;
