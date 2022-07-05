@php
  $screenName = "注文完了";
  $functionId = 'member-order';
@endphp
@extends('member.layouts.app')

@section('content')
<div id="order">
	    <section class="sec01">
        <h2> THANK YOU</h2>
        <p class="jp">注文完了</p>

        <div class="box_order">
          <p class="ttl_fin">ご注文ありがとうございました。</p>
          <p class="txt_fin">お客様のご注文番号は「{{ $orderid }}」です。<br>
                             お客様のご登録メールアドレス宛に注文確認メールを送信いたします。</p>
        </div>
        <div class="btn_area">
		      <input class="button" onClick="location.href='/'" value="トップページへ">
		    </div>
	    </section>
</div>
@endsection

@section('information')
  @include('member.layouts.information')
@endsection

@section('sidenav')
  @include('member.layouts.sidenav')
@endsection

@push('app-style')
  <link href="{{asset('css/member/style.css')}}" rel="stylesheet">
@endpush

@push('app-script')
@endpush
