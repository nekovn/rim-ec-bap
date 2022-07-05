/**
 * WYSIWY設定
 */
class Wysiwyg {
  /**
   * 指定したコンテンツにWYSIWYを設定する
   * @param {string} editor 対象id属性
   */
  static setup(editor) {
    $(editor).trumbowyg('destroy');
    $(editor).trumbowyg({
      lang: 'ja',
      btnsDef: {
        image: {
          dropdown: ['insertImage', 'noembed'],
          ico: 'insertImage'
        }
      },
      btns: [
        ['viewHTML'],
        ['formatting'],
        ['strong', 'em'],
        ['fontsize'],
        ['foreColor', 'backColor'],
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['indent', 'outdent'],
        ['unorderedList', 'orderedList'],
        ['horizontalRule'],
        ['removeformat'],
        ['image'],
        ['link'],
        ['noembed'],
        ['table']
      ]
    });
  }
}
export default Wysiwyg;
