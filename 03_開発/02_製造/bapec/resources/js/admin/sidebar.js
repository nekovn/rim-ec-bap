//サイドバー調整(sidevar.js参照)
let bar = document.getElementById('sidebar');
const body = document.getElementsByClassName('c-body')[0];

const CLASS_NAME_BACKDROP = 'c-sidebar-backdrop';
const CLASS_NAME_FADE = 'c-fade';
const CLASS_NAME_SHOW = 'c-show';
var _backdrop;
function _isMobile() {
  return Boolean(
    window.getComputedStyle(bar, null).getPropertyValue('--is-mobile')
  );
}
//sidebar open
bar.addEventListener('classadded', function () {
  body.classList.add('show-menu'); //sidebar表示時にレスポンシブしたくない場合。_custom.scssのCSSを生かす

  if (_isMobile()) {
    return;
  }
  _backdrop = document.createElement('div');
  _backdrop.className = CLASS_NAME_BACKDROP;

  _backdrop.classList.add(CLASS_NAME_FADE);

  document.body.appendChild(_backdrop);
  // _backdrop.offsetHeight);

  _backdrop.classList.add(CLASS_NAME_SHOW);

  _backdrop.addEventListener('click', function () {
    $('.c-header-toggler').trigger('click');
  });
});
// sidebar close
bar.addEventListener('classremoved', function () {
  body.classList.remove('show-menu');
  removeBackdrop();
});

function removeBackdrop() {
  if (_backdrop) {
    _backdrop.parentNode.removeChild(_backdrop);
    _backdrop = null;
  }
}
