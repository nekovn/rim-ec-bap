const AG_GRID_LOCALE_JA = {};
Object.keys(AG_GRID_LOCALE_EN).forEach((key) => {
  AG_GRID_LOCALE_JA[key] = AG_GRID_LOCALE_EN[key];
});
Object.assign(AG_GRID_LOCALE_JA, {
  noRowsToShow: '該当データがありません',
  to: ' - ',
  of: ' / ',
  page: 'ページ'
});
