<?php
/**
 *  顧客マスタblade
 */
  $screenName = '顧客マスタ';
  $functionId = 'customers';
?>
@extends('admin.layouts.template.simple-crud')

{{-- 検索部 --}}
@section('search-condition')
<div class="row col-12 mb-2">
  <div class="form-group form-inline mr-2">
    {{Form::label('search-id', '顧客ID', ['class'=>'mr-2 col-form-label'])}}
    {{Form::text('search-id', null, ['class'=>'form-control ','size'=>'30'])}}
  </div>
  <div class="form-group form-inline mr-2">
    {{Form::label('search-full_name', '氏名', ['class'=>'mr-2 col-form-label'])}}
    {{Form::text('search-full_name', null, ['class'=>'form-control ','size'=>'30'])}}
  </div>
  <div class="form-group form-inline mr-2">
    {{Form::label('search-full_name_kana', 'フリガナ', ['class'=>'mr-2 col-form-label'])}}
    {{Form::text('search-full_name_kana', null, ['class'=>'form-control ','size'=>'30'])}}
  </div>
  <div class="form-group form-inline">
    {{Form::label('search-email', 'メールアドレス', ['class'=>'mr-2 col-form-label'])}}
    {{Form::text('search-email', null, ['class'=>'form-control ','size'=>'30'])}}
  </div>
</div>
@endsection

{{-- 顧客編集部分 --}}
@section('detail')
<div class="col-md-11">
  <div class="row form-group">
    <div class="col-3">
      {{Form::labelEx('surname', '姓', ['class'=>'required'])}}
      {{Form::textEx('surname', null, ['class'=>'form-control', 'maxlength'=>'30', 'required', 'tabindex'=>'1'])}}
    </div>
    <div class="col-3">
      {{Form::labelEx('name', '名', ['class'=>'required'])}}
      {{Form::textEx('name', null, ['class'=>'form-control', 'maxlength'=>'30', 'required', 'tabindex'=>'2'])}}
    </div>
    <div class="col-1"></div>
    <div class="col-3">
      {{Form::labelEx('customer_rank', '会員ランク')}}
      {{Form::textEx('customer_rank', null, ['class'=>'form-control customer_rank'])}}
    </div>
    <div class="col-2">
      {{Form::labelEx('point', '保有ポイント数')}}
      {{Form::textEx('point', null, ['class'=>'form-control point'])}}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-3">
      {{Form::labelEx('surname_kana', '姓(フリガナ)', ['class'=>'required'])}}
      {{Form::textEx('surname_kana', null, [
        'class'=>'form-control',
        'maxlength'=>'60',
        'required',
        'data-parsley-zenkana',
        'tabindex'=>'3'
      ])}}
    </div>
    <div class="col-3">
      {{Form::labelEx('name_kana', '名(フリガナ)', ['class'=>'required'])}}
      {{Form::textEx('name_kana', null, [
        'class'=>'form-control',
        'maxlength'=>'60',
        'required',
        'data-parsley-zenkana',
        'tabindex'=>'4'
      ])}}
    </div>
    <div class="col-1"></div>
    <div class="col-5">
      {{Form::labelEx('birthday_year', '生年月日', ['class'=>'required'])}}
      <div class="row form-group">
        {{Form::selectRange('birthday_year', $selections['fromYear'], $selections['toYear'], null, [
          'class'=>'form-control birthday-year',
          'data-parsley-date'=>'birthday',
          'tabindex'=>'11'
        ])}}
        <span class="birth_label">年</span>
        {{Form::selectRange('birthday_month', 1, 12, null, [
          'class'=>'form-control birthday-month',
          'data-parsley-date'=>'birthday',
          'tabindex'=>'12'
        ])}}
        <span class="birth_label">月</span>
        {{Form::selectRange('birthday_day', 1, 31, null, [
          'class'=>'form-control birthday-day',
          'data-parsley-date'=>'birthday',
          'tabindex'=>'13'
        ])}}
        <span class="birth_label">日</span>
      </div>
    </div>
  </div>
  <div class="row form-group">
    <div class="col-6">
      {{Form::labelEx('tel', '電話番号', ['class'=>'required'])}}
      {{Form::textTel('tel', null, ['class'=>'form-control', 'required', 'tabindex'=>'5'])}}
    </div>
    <div class="col-1"></div>
    <div class="col-5">
      {{Form::labelEx('gender', '性別', ['class'=>'required'])}}
      {!!Form::gender('gender', ['required', 'tabindex'=>'14'])!!}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-3">
      {{Form::labelEx('zip', '郵便番号', ['class'=>'required'])}}
      {{Form::postCode('zip', null, ['id'=>'zip', 'class'=>'', 'required', 'tabindex'=>'6'],
                        ['autocomplete'=>[
                            'selector-pref'=>'#prefcode',
                            'selector-city'=>'#addr_1',
                            'selector-town'=>'#addr_2']])}}
    </div>
    <div class="col-3">
      {{Form::labelEx('prefcode', '都道府県', ['class'=>'required'])}}
      {{Form::pref('prefcode', null, ['id'=>'prefcode', 'class'=>'form-control', 'required', 'tabindex'=>'7'])}}
    </div>
    <div class="col-1"></div>
    <div class="col-5">
      {{Form::labelEx('email', 'メールアドレス', ['class'=>'required'])}}
      {{Form::emailEx('email', null, ['class'=>'form-control', 'maxlength'=>'254', 'required', 'tabindex'=>'15'])}}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-6">
      {{Form::labelEx('addr_1', '市区町村', ['class'=>'required'])}}
      {{Form::textEx('addr_1', null, ['id'=>'addr_1', 'class'=>'form-control', 'maxlength'=>'35', 'required', 'tabindex'=>'8'])}}
    </div>
    <div class="col-1"></div>
    <div class="col-2">
      {{Form::labelEx('password', 'パスワード')}}
      {{Form::passwordEx('password', ['class'=>'form-control', 'maxlength'=>'100', 'tabindex'=>'16'])}}
    </div>
    <div class="col-2">
      {{Form::labelEx('password_confirm', 'パスワード(確認用)')}}
      {{Form::passwordEx('password_confirm', [
        'class'=>'form-control',
        'data-parsley-validate-if-empty'=>true,
        'data-parsley-equal-to'=>'#password',
        'data-compare-name'=>'パスワード',
        'maxlength'=>'100',
        'data-html-only',
        'tabindex'=>'17'
      ])}}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-6">
      {{Form::labelEx('addr_2', '町名・番地', ['class'=>'required'])}}
      {{Form::textEx('addr_2', null, ['id'=>'addr_2', 'class'=>'form-control', 'maxlength'=>'63', 'required', 'tabindex'=>'9'])}}
    </div>
    <div class="col-1"></div>
    <div class="col-3">
      {{Form::labelEx('is_login_prohibited', 'ログイン禁止フラグ', ['class'=>'required'])}}
      {!!Form::radios('is_login_prohibited',
        [['value'=>'0', 'label'=>'通常'], ['value'=>'1', 'label'=>'ログイン禁止']],
        ['class'=>'form-control', 'required', 'tabindex'=>'18'],
        '0'
      )!!}
    </div>
    <div class="col-2">
      {{Form::labelEx('is_locked', 'アカウントロックフラグ', ['class'=>'required'])}}
      {!!Form::radios('is_locked',
        [['value'=>'0', 'label'=>'通常'], ['value'=>'1', 'label'=>'ロック']],
        ['class'=>'form-control', 'required', 'tabindex'=>'19'],
        '0'
      )!!}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-6">
      {{Form::labelEx('addr_3', '建物名等')}}
      {{Form::textEx('addr_3', null, ['class'=>'form-control', 'maxlength'=>'63', 'tabindex'=>'10'])}}
    </div>
    <div class="col-1"></div>
    <div id="bcrew_ref_area" class="col-2">
      <div class="w-100">
      {{Form::labelEx('bcrew_customer_id_status', 'アプリ連携 ：')}}
      <span id="bcrew_customer_id_status"></span>
      </div>
      <div class="w-100">
        {{Form::labelEx('bcrews_customer_id_status', 'B-crews連携：')}}
        <span id="bcrews_customer_id_status"></span>
      </div>
    </div>
    <div id="bcrews_ref_area" class="col-3">
      <div id="bcrew_customer_id_wrapper">
        {{Form::labelEx('bcrew_customer_id', 'ID')}}：
        <span id="bcrew_customer_id"></span>
      </div>
      <div id="bcrews_customer_id_wrapper">
        {{Form::labelEx('bcrews_customer_id', 'ID')}}：
        <span id="bcrews_customer_id"></span>
      </div>
    </div>
  </div>
  <div class="row form-group">
    <div class="col-12">
      {{Form::labelEx('remark', 'メモ')}}
      {{Form::textarea('remark', null, ['class'=>'form-control', 'maxlength'=>'4000', 'rows'=>'5', 'tabindex'=>'20'])}}
    </div>
  </div>
</div>
@endsection
@push('app-style')
    <link href="{{mix('css/admin/page/customers.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{mix('js/admin/page/customers.page.js')}}" defer></script>
@endpush
