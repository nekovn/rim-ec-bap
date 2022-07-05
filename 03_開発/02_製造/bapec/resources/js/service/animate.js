/**
 * アニメーションサービス
 *
 * @classdesc path: @js/service/animate.js
 *
 * <p>animate__animated:animate.css(vendor)が要求する<p>
 * <pre>
 *   content-area      : 画面表示エリア
 *   content-area-hide : 画面非表示エリア
 * </pre>
 * <a href="https://animate.style/" target="_blank">参考：Animate.css<a>
 */
class AnimateService {
  'use strict';

  /**
   * @memberof AnimateService
   * @desc アニメーションフェード定義
   * <pre>
   *   <ul>
   *    <li>OUT_RIGHT: 右へフェイドアウト</li>
   *    <li>IN_RIGHT: 右からフェイドイン</li>
   *    <li>OUT_LEFT: 左へフェイドアウト</li>
   *    <li>IN_LEFT: 左からフェイドイン</li>
   *   </ul>
   * </pre>
   */
  static FADE = {
    OUT_RIGHT: 'animate__fadeOutRight',
    IN_RIGHT: 'animate__fadeInRight',
    OUT_LEFT: 'animate__fadeOutLeft',
    IN_LEFT: 'animate__fadeInLeft'
  };

  /**
   * 指定したコンテンツを表示する（画面遷移）
   * @function
   * @memberof AnimateService
   * @param {string} contentId 表示したいコンテンツを示すid属性
   * @param {string} areaId エリアを示すid属性
   */
  static forward(contentId, areaId = '') {
    AnimateService.showContent(contentId, areaId, AnimateService.FADE.IN_RIGHT);
  }
  /**
   * 指定したコンテンツを表示する（前画面に戻る）
   * @function
   * @memberof AnimateService
   * @param {string} contentId 表示したいコンテンツを示すid属性
   * @param {string} areaId エリアを示すid属性
   */
  static backward(contentId, areaId = '') {
    AnimateService.showContent(contentId, areaId, AnimateService.FADE.IN_LEFT);
  }
  /**
   * 指定したコンテンツを表示する
   * <pre>
   *   htmlの要素内のclass属性に「content-area」が付与されている要素を非表示にする。
   *   htmlの要素内のclass属性に「content-area-hide」が付与されている要素を表示する。
   * </pre>
   * @function
   * @memberof AnimateService
   * @param {string} contentId 表示したいコンテンツを示すid属性
   * @param {string} areaId エリアを示すid属性
   * @param {string} pattern AnimateService.FADEのパターン
   */
  static showContent(contentId, areaId = '', pattern) {
    // class属性「content-area」が付与されている要素を非表示にする
    $(`${areaId} .content-area`)
      .addClass(AnimateService.FADE.IN_RIGHT)
      .removeClass(AnimateService.FADE.IN_LEFT);
    $(`${areaId} .content-area`).addClass('content-area-hide');
    $(`${areaId} .content-area`).removeClass('content-area');

    // 引数に指定したid属性のコンテンツを表示する
    $(`${areaId} #${contentId}`).removeClass('content-area-hide');
    $(`${areaId} #${contentId}`).addClass('content-area');
    $(`${areaId} #${contentId}`).addClass(pattern);
  }
}
export default AnimateService;
