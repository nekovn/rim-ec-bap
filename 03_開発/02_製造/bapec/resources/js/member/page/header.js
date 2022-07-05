import {XHRCrudMethod, XhrParam} from '@js/service/xhr';
import ImageService from '@js/service/image';

/**
 * ヘッダクラス
 */
class Header {
  /**
   * コンストラクタ
   */
  constructor() {
    // NO IMAGE設定
    $('.img-load-chk').each((i, e) => {
      ImageService.loadImage(e);
    });

    /**
     * 検索ボタン押下
     */
    $('.header_search_btn').on('click', (e) => {
      $('.header_search_condition_c').val(''); // 細カテゴリパラメータは除去
      Header.doSearch($(e.target).closest('.header_search_form'));
      return false;
    });

    /**
     * キーワード検索
     */
    $('.header_search_condition_k').on('keydown', (e) => {
      // エンターキー押下による検索
      if (e.key === 'Enter') {
        $('.header_search_condition_c').val(''); // 細カテゴリパラメータは除去
        Header.doSearch($(e.target).closest('.header_search_form'));
        return false;
      }
    });
  }

  /**
   * 検索実行
   */
  static doSearch(form) {
    form = typeof form !== 'undefined' ? form : $('#header_search_form');
    form.find('input,select').each((e, target) => {
      if ($(target).val() == '') {
        $(target).prop('disabled', true);
      }
    });
    form.submit();
  }

  /**
   * ヘッダー部、カート点数表示更新
   */
  static doUpdateHeaderCount() {
    const xhrParam = new XhrParam('/api/member/cart/info');
    xhrParam.spinnerParam.show = false;
    const successHandler = (res) => {
      if (res.count >= 0) {
        $('#cartCount')
          .attr('data-badge-top-right', res.count)
          .removeClass('d-none');
      }
    };
    XHRCrudMethod.get(xhrParam, successHandler);
  }
}
export {Header};
new Header('header');

// カート内件数取得
Header.doUpdateHeaderCount();
