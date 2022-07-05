<?php
/**
 *  ユーザーマスタblade
 */
  $screenName = 'ユーザーマスタ';
  $functionId = 'users';
?>
@extends('admin.layouts.template.simple-crud')

{{-- 検索部 --}}
@section('search-condition')
  <div class="form-group row">
  {{Form::label('search-code', '社員番号', ['class'=>'mr-2 col-form-lable'])}}
  {{Form::text('search-code', null, ['class'=>'form-control col-md-9','size'=>'50'])}}
</div>
<div class="form-group row">
  {{Form::label('search-name', '氏名', ['class'=>'mr-2 col-form-lable'])}}
  {{Form::text('search-name', null, ['class'=>'form-control col-md-9','size'=>'50'])}}
</div>
@endsection

{{-- ユーザー編集部分 --}}
@section('detail')
  <div class="col-md-6 pl-3">
    <div class="form-group">
      {{Form::label('code', '社員番号', ['class'=>'required'])}}
      {{Form::textEx('code', null, ['class'=>'form-control', 'maxlength'=>'5', 'required'])}}
    </div>
    <div class="form-group">
      {{Form::label('email', 'メールアドレス', ['class'=>'required'])}}
      {{Form::emailEx('email', null, ['class'=>'form-control', 'required'])}}
    </div>
    <div class="form-group">
      {{Form::label('name', '氏名', ['class'=>'required'])}}
      {{Form::textEx('name', null, ['class'=>'form-control', 'maxlength'=>'50', 'required'])}}
    </div>
    <div class="form-group">
      {{Form::label('password', 'パスワード')}}
      {{Form::passwordEx('password', ['class'=>'form-control', 'maxlength'=>'32'])}}
    </div>
    <div class="form-group">
      {{Form::labelEx('password_confirm', 'パスワード(確認用)')}}
      {{Form::passwordEx('password_confirm', ['data-parsley-validate-if-empty' => true, 'data-parsley-equal-to' => '#password', 'data-compare-name' => 'パスワード', 'maxlength'=>'32', 'data-html-only', 'required'])}}
    </div>
  </div>
@endsection
{{-- 権限部分 --}}
@section('etcsection')
<div id="{{$functionId}}-detail-area2" class="animate__animated content-area-hide detail-area">
    <form id="form-{{$functionId}}-detail2" class="pl-3">
      <div class="card">
        <div class="card-header detail-title">
          <b>権限編集</b>
        </div>
        <div class="card-body">
          <div class="btn-header">
            {!! Form::backButton(['id' => $functionId.'-btn-back2', 'class' => 'btn-sm ']) !!}
          </div>
            <div id="user-auth">
            <div class="row">
                <div class="col-12">
                    <span id="{{$functionId}}-detail-area2-username">aaaaa</span>
                </div>
            </div>
            <div class="row mt-1">
                <div id="total-count" class="col-12 text-right"></div>
                {!! Form::grid($functionId .'-auth',[],false) !!}
            </div>
            </div>
        
            <div class="btn-footer">
            <x-auth-button kbn="update" id="{{$functionId}}-btn-update2"/>
            
            </div>

          </div>
        </div>
    </form>
  </div>
@endsection
@push('app-style')
    <link href="{{mix('css/admin/page/users.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{mix('js/admin/page/users.page.js')}}" defer></script>
@endpush
