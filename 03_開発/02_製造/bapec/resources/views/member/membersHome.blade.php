@php
$screenName = "マイページトップ"
@endphp
@extends('member.layouts.app')

@section('content')
<div id="mypage">
    <section class="sec01">
      <h2>PROFILE</h2>
      <p class="jp">会員情報変更</p>
      <p class="name"><span class="name01">{{ Auth::user()->surname }}</span> <span class="name02">{{ Auth::user()->name }}</span> 様</p>

      <ul class="btn_menu">
        <li>
          <a href="{{ route('members.edit') }}">
            <p class="icon"><img src="{{ asset('images/mypage/icon_profile.png') }}" alt=""></p>
            <p class="ttl">会員情報</p>
          </a>
        </li>
        <li>
          <a href="{{ route('order.history') }}">
            <p class="icon"><img src="{{ asset('images/mypage/icon_cart.png') }}" alt=""></p>
            <p class="ttl">購入履歴</p>
          </a>
        </li>
      </ul>
    </section>
</div>
@endsection

@section('information')
  @include('member.layouts.information')
@endsection

@section('sidenav')
  @include('member.layouts.sidenav')
@endsection
