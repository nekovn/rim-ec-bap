<header class="clearfix">
	<h1>
		<a href="/"><img src="{{asset('images/common/logo.svg')}}" alt="B-crew ONLINE SHOP"></a>
	</h1>

	<ul class="nav">
		<!-- <li><a href="#"><img src="{{asset('images/common/icon_head01.svg')}}" alt="ポイント"></a></li> -->
		<li><a href="{{ url('/static/info/shipping') }}"><img src="{{asset('images/common/icon_head02.svg')}}" alt="配送"></a></li>
		<li><a href="{{url('/cart')}}">
			<span id="cartCount" data-badge-top-right="0" class="d-none"></span>
			<img src="{{asset('images/common/icon_head03.svg')}}" alt=""></a>
		</li>
		<!-- <li><a href="#"><img src="{{asset('images/common/icon_head04.svg')}}" alt="お気に入り"></a></li> -->
		<li><a href="{{url('/members/home')}}"><img src="{{asset('images/common/icon_head05.svg')}}" alt="マイページ非ログイン"></a></li>
	</ul>

	<p class="menu-btn" id="js__btn">&nbsp;</p>

	<div class="menu" id="js__nav">
		<div class="menu-inner">
			<!--
			<p class="login"><a href="#"><span>ログインはこちら</span></a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="register">新規会員登録</a></p>
			-->
			<p class="ttl01"><img src="{{asset('images/common/ttl_search.svg')}}" alt="SEARCH"></p>

			<div class="search">
				@include('member.layouts.header-search-form')
			</div>
			<div id="acNav" class="acNav-container">
				<h4 class="acNav-title js-acNav-title">カテゴリーから探す</h4>
				<div class="acNav-content">
					@foreach ($categoryClass1 as $code => $categoryName)
						<p><a href="{{ url('/goods?c=' . $code)}}">{{$categoryName}}</a></p>
					@endforeach
				</div>
				<!--<h4 class="acNav-title js-acNav-title">ブランドから探す</h4>
				<div class="acNav-content">
					<p>N.（エヌドット）</p>
					<p>BJC</p>
					<p>ナプラ</p>
				</div>-->
			</div>
			<!--<ul class="nav01">
				<li><a href="#">ブランドから探す</a></li>
				<li><a href="#">カテゴリから探す</a></li>
				<li><a href="#">ランキングから探す</a></li>
				<li><a href="#">新着から探す</a></li>
				<li><a href="#">レビューから探す</a></li>
				<li class="two"><a href="#">ナチュラルコスメ<br>から探す</a></li>
			</ul>-->
			<p class="ttl02"><img src="{{asset('images/common/ttl_contents.svg')}}" alt="CONTENTS"></p>
			<ul class="nav02">
				<!--
				<li>
					<a href="#"><img src="{{asset('images/common/icon_menu01.svg')}}" alt="ポイント"></a>
				</li>
				-->
				<li>
					<a href="{{ url('/static/info/shipping') }}"><img src="{{asset('images/common/icon_menu02.svg')}}" alt="配送"></a>
				</li>
				<li>
					<a href="{{url('/cart')}}">
					<span id="cartCount" data-badge-top-right="0" class="d-none"></span>
					<img src="{{asset('images/common/icon_menu03.svg')}}" alt="カート"></a>
				</li>
				<!--
				<li>
					<a href="#"><img src="{{asset('images/common/icon_menu04.svg')}}" alt="お気に入り"></a>
				</li>
				-->
			</ul>
			<ul class="nav01">
				<!--<li><a href="#">特集</a></li>
				<li><a href="#">コラム</a></li>
				<li><a href="#">スタッフレビュ</a></li>
				<li><a href="#">お知らせ一覧</a></li>
				<li class="two"><a href="#">B-crew OnlineShop<br>とは？</a></li>-->
				<li><a href="https://www.b-crew.jp/ec-support/?id={{ Auth::check() ? Auth::user()->bcrew_customer_id : '' }}" target="_blank">お問い合わせ</a></li>
				<li><a href="{{ url('/static/info/shipping') }}">ご利用ガイド</a></li>
				<li><a href="https://b-a-p.co.jp/company.html" target="_blank">企業情報</a></li>
				<li><a href="{{ url('/static/info/privacy') }}">プライバシーポリシー</a></li>
				<li><a href="{{ url('/static/info/tokusho') }}">特定商取引法に基づく表示</a></li>
			</ul>
			<p class="ttl03"><img src="{{asset('images/common/ttl_sns.svg')}}" alt="SNS"></p>
			<ul class="sns">
				<!--<li>
					<a href="#"><img src="{{asset('images/common/icon_menu_line.svg')}}" alt="LINE"></a>
				</li>-->
				<li>
					<a href="https://www.instagram.com/beast00825/"  target="_blank"><img src="{{asset('images/common/icon_menu_insta.svg')}}" alt="Instagram"></a>
				</li>
				<!--<li>
					<a href="#"><img src="{{asset('images/common/icon_menu_youtube.svg')}}" alt="YouTube"></a>
				</li>-->
			</ul>
		</div>
	</div>
</header>

@include('member.layouts.gnav')

@push('app-script')
	<script src="{{mix('js/member/page/header.js')}}" defer></script>
	<script type="text/javascript" src="{{asset('vendor/jquery/jquery.matchHeight.js')}}"></script>
	<script>
		$(function() {
			$('header .menu .nav01 li a').matchHeight();
			$('.sec01 .item_list li figure + div').matchHeight();
			$('.fnav li a').matchHeight();
		});
	</script>
@endpush
