@php
$screenName = "B-crewアプリ";
$functionId = 'app-info';
@endphp

@extends('member.layouts.base')

@section('breadcrumb')
@endsection

@section('content')
<div id="info">
  <section class="sec01">
      <h2>B-crew</h2>
      <p class="jp">B-crewアプリ</p>

      <p>サイトをご利用いただくためには、<br>B-crewアプリのインストールが必要です。</p>
      <br>
      <p>既にアプリをインストール済みの方は、<br>「ショップ」アイコンからご利用ください。</p>

      <dl class="appinfo-list">
        <dt class="appinfo-list__info">インストールはこちらから</dt>
        <dd class="appinfo-list__body">
          <ul>
            <li>
              <a href="https://apps.apple.com/jp/app/b-crew/id1551380642">
                <p class="icon"><img src="{{asset('images/common/bnr_AppStore.svg')}}" alt="App Strore"></p>
              </a>
            </li>
            <li>
              <a href="https://play.google.com/store/apps/details?id=jp.co.bap.salonapp.customer">
                <p class="icon"><img src="{{asset('images/common/bnr_GooglePlay.svg')}}" alt="Google Play"></p>
              </a>
            </li>
          </ul>
        </dd>
      </dl>
  </section>
</div>
@endsection

@section('sidenav')
  @include('member.layouts.sidenav')
@endsection

@section('information')
  @include('member.layouts.information')
@endsection

@push('app-script')
@endpush
