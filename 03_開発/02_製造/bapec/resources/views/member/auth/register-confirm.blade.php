@php
  use App\Enums\FlagDefine;
  $screenName = "会員登録確認画面"
@endphp
@extends('member.layouts.base')
@section('base-content')
<div id="wrapper">
  @include('member.layouts.header')
  {{Form::model($member, ['id' => 'form-member-register-confirm', 'url' => route('member.register')])}}
    <article id="login">
      <section class="main">
        <div class="inner">
          <h3>会員登録画面</h3>
          @if ($errors->any())
            <div class="alert alert-danger">
              @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
              @endforeach
            </div>
          @endif
          <dl>
            <dt>
              {{Form::labelEx('name', 'お名前')}}
            </dt>
            <dd>
              {{old('name', $member['name'])}}
              {{Form::hidden('name', old('name', $member['name']))}}
            </dd>
          </dl>
          <dl>
            <dt>
              {{Form::labelEx('name_read', 'よみ')}}
            </dt>
            <dd>
              {{old('name_read', $member['name_read'])}}
              {{Form::hidden('name_read', old('name_read', $member['name_read']))}}
            </dd>
          </dl>
          <dl>
            <dt>
              {{Form::labelEx('email', 'メールアドレス', ['class' => 'required'])}}
            </dt>
            <dd>
              {{old('email', $member['email'])}}
              {{Form::hidden('email', old('email', $member['email']))}}
            </dd>
          </dl>
          <dl>
            <dt>
              {{Form::labelEx('tel', '連絡先', ['class' => 'required'])}}
            </dt>
            <dd>
              {{old('tel', $member['tel'])}}
              {{Form::hidden('tel', old('tel', $member['tel']))}}
            </dd>
          </dl>
          <dl>
            <dt>
              {{Form::labelEx('birthday', '生年月日', ['class' => 'required'])}}
            </dt>
            <dd>
              {{old('birthday', $member['birthday'])}}
              {{Form::hidden('birthday', old('birthday', $member['birthday']))}}
              <span class="age">年齢 <span id="age" class="ml-3"></span>歳</span>
            </dd>
          </dl>
          <dl>
            <dt>
              {{Form::labelEx('gender', '性別', ['class' => 'required'])}}
            </dt>
            <dd>
              {{SystemHelper::getCodes(CodeDefine::GENDER)[old('gender', $member['gender'])]}}
              {{Form::hidden('gender', old('gender', $member['gender']))}}
            </dd>
          </dl>
          <dl>
            <dt>
              {{Form::labelEx('post_cd', '郵便番号', ['class' => 'required'])}}
            </dt>
            <dd>
              {{old('post_cd', $member['post_cd'])}}
              {{Form::hidden('post_cd', old('post_cd', $member['post_cd']))}}
            </dd>
          </dl>
          <dl>
            <dt>
              {{Form::labelEx('pref_cd', '住所', ['class' => 'required'])}}
            </dt>
            <dd>
              {{SystemHelper::getCodes(CodeDefine::PREF_CD)[old('pref_cd', $member['pref_cd'])]}}
              {{Form::hidden('pref_cd', old('pref_cd', $member['pref_cd']))}}
              <p class="mt">
                {{old('municipality', $member['municipality'])}}
                {{Form::hidden('municipality', old('municipality', $member['municipality']))}}
              </p>
              <p class="mt">
                {{old('address', $member['address'])}}
                {{Form::hidden('address', old('address', $member['address']))}}
              </p>
              <p class="mt">
                {{old('building_name', $member['building_name'])}}
                {{Form::hidden('building_name', old('building_name', $member['building_name']))}}
              </p>
            </dd>
          </dl>
          <dl>
            <dt>
              {{Form::labelEx('password', 'パスワード')}}
            </dt>
            <dd>
              ********
              {{Form::hidden('password', old('password', $member['password']))}}
            </dd>
          </dl>
          <div class="btn_area">
            <input class="button" type="button" id="btn-back" value="戻る" data-url="{{route('member.register-entry-back')}}">
            <input class="button" type="submit" name="submit" value="登録する">
          </div>
        </div>
      </section>
    </article>
  {{Form::close()}}
</div>
@endsection
@section('app-script')
　　<script src="{{mix('js/page/members.register.confirm.page.js')}}" defer></script>
@endsection