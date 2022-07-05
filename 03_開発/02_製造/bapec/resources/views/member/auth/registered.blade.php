@php
  use App\Enums\FlagDefine;
  $screenName = "会員登録完了画面"
@endphp
@extends('member.layouts.base')
@section('base-content')
<div id="wrapper">
  @include('member.layouts.header')
  <article id="login">
    <section class="main">
      <div class="inner">
        <h3>会員登録画面</h3>
        {!!link_to_route('member.login', 'ログイン画面に戻る')!!}
      </div>
    </section>
  </article>
</div>
@endsection
