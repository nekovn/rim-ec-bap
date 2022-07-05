@php
  $screenName = "注文完了";
  $functionId = 'member-order';
@endphp
@extends('member.layouts.app')

@section('content')
  <article id="order" class="low">

    <div id="main">
      <section>
        <p class="ttl_page">{{ $screenName }}</p>

        <div class="box_order">
          <p class="ttl_fin">ご注文ありがとうございました。</p>
          <p class="txt_fin">お客様のご注文番号は「xxxxxxx」です。<br>
            お客様のご登録メールアドレス宛に注文確認メールを送信いたします。</p>
        </div>

        <div class="btn_area">
          <input class="button" onClick="location.href='index.html'" value="トップページへ">
        </div>
      </section>
    </div>

    @include('member.layouts.sidenav')
  </article>
@endsection

@push('app-style')
  <link href="{{asset('css/member/style.css')}}" rel="stylesheet">
@endpush

@push('app-script')
  <script src="{{mix('js/member/page/member.order.page.js')}}" defer></script>
@endpush
