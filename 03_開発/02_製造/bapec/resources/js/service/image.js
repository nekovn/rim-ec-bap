/**
 * 画像に関するユーティリティサービス
 *
 * @classdesc @js/service/image.js
 */
class ImageService {
  /**
   * targetのsrcを読み込み、取得できなかったらNO IMAGEを表示
   * @param {string} target
   */
  static loadImage(target) {
    $(target).addClass('d-none');
    const onLoadHandler = () => {
      $(target).addClass('d-inline');
    };
    const onErrorHandler = () => {
      $(target).replaceWith(
        '<div class="no-image"><div class="no-image-inner"></div></div>'
      );
    };
    const src = $(target).attr('src');
    const img = new Image();
    img.onload = onLoadHandler;
    img.onerror = onErrorHandler;
    img.src = src;
  }
}
export default ImageService;
