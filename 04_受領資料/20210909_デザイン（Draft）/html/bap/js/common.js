

/*--------------------------------------------------------------------
　アコーディオン
--------------------------------------------------------------------*/
$(function(){
	$(".caution > dd").after().hide();
	$(".caution > dt").click(function(){
		$(this).next().slideToggle();
		$(this).toggleClass("open");
	});
});

$(function(){
	$(".accordion + .movie").after().hide();
	$(".accordion").click(function(){
		$(this).next().slideToggle();
		$(this).toggleClass("open");
	});
});



/*--------------------------------------------------------------------
　タブ切り替え
--------------------------------------------------------------------*/
$(function() {
	$('.tab li').click(function() {
		var index = $('.tab li').index(this);
		$('.content_news > li').css('display','none');
		$('.content_news > li').eq(index).css('display','block');
		$('.tab li').removeClass('select');
		$(this).addClass('select')
	});
});




/*--------------------------------------------------------------------
　スマートフォン　ハンバーガーメニュー
--------------------------------------------------------------------*/
$(function() {
  $("#panel-btn").click(function() {
    $(".menu-box").slideToggle(200);
    $("#panel-btn-icon").toggleClass("close");
    return false;
  });
  $("li.pagelink a").on("click", function() {
    $(".menu-box").slideToggle();
    $("#panel-btn-icon").removeClass("close");
  });
});

$(function(){
	$(".down_sp").click(function(){
		$(this).next(".sub_sp").slideToggle();
		$(this).toggleClass("open");
	});
});



/*--------------------------------------------------------------------
　スムーズスクロール
--------------------------------------------------------------------*/
$(function(){
   $('a[href^=#]' + 'a:not(.non-scroll)').click(function() {
      var speed = 400;
      var href= $(this).attr("href");
      var target = $(href == "#" || href == "" ? 'html' : href);
      var position = target.offset().top;
      $('body,html').animate({scrollTop:position}, speed, 'swing');
      return false;
   });
});



/*--------------------------------------------------------------------
　ページトップ
--------------------------------------------------------------------*/
$(function() {
	var topBtn = $('#pageTop');	
	topBtn.hide();
	$(window).scroll(function () {
		if ($(this).scrollTop() > 150) {
			topBtn.fadeIn();
		} else {
			topBtn.fadeOut();
		}
	});
	topBtn.click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 500);
		return false;
	});
});


/*--------------------------------------------------------------------
　ヘッダー固定　ズレ
--------------------------------------------------------------------*/
if(navigator.userAgent.match(/(iPhone|iPad|iPod|Android)/)){
// ページ内リンク
$(function () {
  var headerHight = 70; //ヘッダの高さ
  $('a[href^=#]').click(function(){
  var href= $(this).attr("href");
  var target = $(href == "#" || href == "" ? 'html' : href);
  var position = target.offset().top-headerHight; 
  $("html, body").animate({scrollTop:position}, 550, "swing");
  return false;
  });
});


// ページ外リンク
$(window).on('load', function() {
    var headerHight = 70; //ヘッダの高さ
    if(document.URL.match("#")) {
    var str = location.href ;
    var cut_str = "#";
    var index = str.indexOf(cut_str);
    var href = str.slice(index);
    var target = href;
    var position = $(target).offset().top - headerHight;
    $("html, body").scrollTop(position);
    return false;
  }
});
}



/*--------------------------------------------------------------------
　多言語切り替え
--------------------------------------------------------------------*/
$(function () {
  // プルダウン変更時に遷移
  $('select[name=pulldown1]').change(function() {
    if ($(this).val() != '') {
      window.location.href = $(this).val();
    }
  });
  // ボタンを押下時に遷移
  $('#location').click(function() {
    if ($(this).val() != '') {
      window.location.href = $('select[name=pulldown2]').val();
    }
  });
});






