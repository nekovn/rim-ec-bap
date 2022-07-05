@php
$screenName = "ログイン画面";
$functionId = "login";
@endphp
@extends('member.layouts.app')

@section('breadcrumb')
@endsection

@section('content')
  <section class="sec01">
    <h2>LOGIN</h2>
    <p class="jp">ログイン</p>
    {{Form::open(['url' => route('member.login')])}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-triangle"></i>
          {{ \Lang::get('messages.E.validation') }}
          @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      @endif
      <dl>
        <dt>メールアドレス</dt>
        <dd>
          {{Form::textEx('code', old('code'), ['class'=>'text01', 'placeholder'=>'ログインID'])}}
        </dd>
        <dt>パスワード</dt>
        <dd>
          {{Form::passwordEx('password', ['class'=>'text01', 'placeholder'=>'パスワード', 'autocomplete'=>'off'])}}
        </dd>
      </dl>
      <div class="btn_area">
        <input class="button" type="submit" name="submit" value="ログイン">
      </div>
    {{Form::close()}}
    <!-- <p class="forget"><a href="#">パスワードを忘れた方はこちら</a></p> -->
  </section>
@endsection