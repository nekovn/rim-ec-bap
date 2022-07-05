/*--------------------------------------------------------------------
　スマートフォン　ハンバーガーメニュー
--------------------------------------------------------------------*/
$(function() {
    var $body = $('body');

    //開閉用ボタンをクリックでクラスの切替え
    $('#js__btn').on('click', function() {
        $body.toggleClass('open');
    });

    //メニュー名以外の部分をクリックで閉じる
    $('.open #js__nav').on('click', function() {
        $body.removeClass('open');
    });
});



/*--------------------------------------------------------------------
　お気に入り　ハートボタン
--------------------------------------------------------------------*/
$(function() {
    $('.heart').on('click', function(event) {
        event.preventDefault();
        $(this).toggleClass('active');
    });
});



/*--------------------------------------------------------------------
　スムーズスクロール
--------------------------------------------------------------------*/
//$(function() {
//    $('a[href^=#]' + 'a:not(.non-scroll)').click(function() {
//        var speed = 400;
//        var href = $(this).attr("href");
//        var target = $(href == "#" || href == "" ? 'html' : href);
//        var position = target.offset().top;
//        $('body,html').animate({ scrollTop: position }, speed, 'swing');
//        return false;
//    });
//});



/*--------------------------------------------------------------------
　ページトップ
--------------------------------------------------------------------*/
$(function() {
    var topBtn = $('#pageTop');
    topBtn.hide();
    $(window).scroll(function() {
        if ($(this).scrollTop() > 150) {
            topBtn.fadeIn();
        } else {
            topBtn.fadeOut();
        }
    });
    topBtn.click(function() {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
});

/*------------------------------------------------------------
グローバルナビゲーションアコーディオン
------------------------------------------------------------*/
$(function() {
    $('.js-acNav-title').on('click', function() {
        $(this).next().slideToggle(200);
        $(this).toggleClass('open', 200);
    });
});