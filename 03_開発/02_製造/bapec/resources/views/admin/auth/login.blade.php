@php
$screenName = "ログイン";
@endphp
@extends('admin.layouts.base')
@push('head-style')
  <link href="{{asset('css/admin/app.css')}}" rel="stylesheet">
@endpush
@section('base-content')
<div class="c-app flex-row align-items-center">
  <div class="container">
    <div class="row justify-content-center mb-3">
      <img src="{{asset('images/logo.png')}}" width="200px">
    </div>
    <div class="row justify-content-center">
      <div class="col-md-5">
        @if ($errors->any())
          <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
        @endif
        <div class="card-group">
          <div class="card p-4">
            <div class="card-body">
              <form method="POST" action="{{ route('admin.login') }}" id="form-login">
                @csrf
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <span class="fas fa-user"></span>
                    </span>
                  </div>
                  {{Form::text('code', old('code'), ['id'=>'code', 'class'=>'form-control', 'placeholder' => 'ユーザーID', 'required', 'autofocus'])}}
                </div>
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <span class="fas fa-key"></span>
                    </span>
                  </div>
                  {{Form::password('password', ['id'=>'password', 'class'=>'form-control', 'placeholder' => 'パスワード', 'required'])}}
                </div>
                <div class="row">
                  <div class="col-12">
                    <button id="btn-login" class="btn btn-info btn-block px-4" type="button">ログイン</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('app-style')
  <link href="{{asset('vendor/jquery-toast/jquery.toast.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/tooltipster-master/css/tooltipster.bundle.min.css')}}" rel="stylesheet" />
  <link href="{{asset('vendor/tooltipster-master/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-borderless.min.css')}}" rel="stylesheet" />
@endsection
@section('app-script')
  <script src="{{asset('vendor/jquery/jquery.min.js')}}" defer></script>
  <script src="{{asset('vendor/coreui/js/coreui.bundle.min.js')}}" defer></script>
  <script src="{{asset('vendor/parsley/parsley.min.js')}}" defer></script>
  <script src="{{asset('vendor/parsley/i18n/ja.js')}}" defer></script>
  <script src="{{asset('vendor/parsley/i18n/ja.extra.js')}}" defer></script>
  <script src="{{asset('vendor/jquery-toast/jquery.toast.min.js')}}" defer></script>
  <script src="{{asset('vendor/tooltipster-master/js/tooltipster.bundle.js')}}" defer></script>
  <script src="{{asset('js/app-config.js')}}" defer></script>
  <script src="{{asset('js/admin/app.js')}}" defer></script>
  <script src="{{mix('js/admin/page/login.page.js')}}" defer></script>
@endsection
