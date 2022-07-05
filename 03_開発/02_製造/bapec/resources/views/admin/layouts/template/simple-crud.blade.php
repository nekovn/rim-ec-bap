@php
/**
 * SimpleCrudテンプレート一覧・詳細画面パターンのメンテナンス画面
 */
use App\Helpers\Util\SystemHelper;
@endphp
@extends('admin.layouts.app')

@section('content')
<div id="{{$functionId}}-list-area" class="animate__animated content-area-hide">
  <div class="container-fluid">
    <div class="fade-in">
      <div class="card ">
        <div class="card-header">
          <b>{{$screenName}}</b>
          <div class="card-header-actions">
            <x-auth-button kbn="create" id="{{$functionId}}-btn-create"/>
          </div>
        </div>
        <div class="card-body">
          {{-- メインCard --}}
          {{-- 検索条件 --}}
          {{Form::open(['class'=>'searchcondition-area','id' =>'form-'.$functionId.'-searchcondition'])}}
            <div class="form-inline">
              @yield('search-condition')
            </div>
            <div class="d-block text-right ml-auto">
              {!! Form::searchButton(['id' => $functionId.'-btn-search', 'class' => '']) !!}
              {!! Form::clearButton(['id' => $functionId.'-btn-clear', 'class' => 'ml-3']) !!}
            </div>
            {{-- 検索結果 --}}
            <div id="{{$functionId}}-search-result" class="d-none">
              <div class="row mt-1">
                {!! Form::grid($functionId) !!}
              </div>
            </div>
          {{Form::close()}}
        </div>
      </div>
    </div>
  </div>
</div>

<div id="{{$functionId}}-detail-area" class="animate__animated content-area-hide detail-area">
  <div class="card">
    <div class="card-header detail-title">
      <b>{{$screenName}}編集</b>
    </div>
    <div class="card-body">
      <div class="btn-header">
        {!! Form::backButton(['id' => $functionId.'-btn-back', 'class' => 'btn-sm']) !!}
    </div>
      {{Form::open(['id'=>'form-'.$functionId.'-detail','class'=>'p-3','files' => true])}}
        @yield('detail')
        <div class="btn-footer">
          <x-auth-button kbn="store" id="{{$functionId}}-btn-store"/>
          <x-auth-button kbn="update" id="{{$functionId}}-btn-update"/>
          <x-auth-button kbn="delete" id="{{$functionId}}-btn-delete" />
        </div>
      {{Form::close()}}
    </div>
  </div>
</div>

@yield('etcsection')

@endsection
@push('head-style')
  <link href="{{asset('vendor/ag-grid/css/ag-grid.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/ag-grid/css/ag-theme-alpine.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/animate/css/animate.min.css')}}" rel="stylesheet">
@endpush
@push('head-script')
<script src="{{asset('vendor/ag-grid/js/ag-grid-community.min.noStyle.js')}}" defer></script>
<script src="{{asset('vendor/ag-grid/i18n/locale.en.js')}}" defer></script>
<script src="{{asset('vendor/ag-grid/i18n/locale.ja.js')}}" defer></script>
@endpush