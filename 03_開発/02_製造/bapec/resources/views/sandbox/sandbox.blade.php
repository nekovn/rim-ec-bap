<?php
  $screenName = 'サンドボックス';
  $functionId = 'sandbox';
?>
@extends('admin.layouts.template.base-card')

@section('main-area')
  {{Form::open(['class'=>'searchcondition-area','id' =>'form-'.$functionId.'-searchcondition'])}}
    <div class="clearfix mt-3">
      <div class="float-left">{{ $recommend['title'] }}</div>
      <div class="d-block float-right ml-auto">
        {!! Form::dispSearchButton(['id' => $functionId.'_xxx_ref_btn', 'class' => 'w-auto'], '新規追加') !!}
      </div>
      {{Form::hidden('search-recommend_id', $recommend['id'], ['class'=>"form-control"])}}
    </div>
    @if (SystemHelper::getAppSettingValue('page.pagination'))
      <label class="col-1 col-form-label text-right">表示件数</label>
      {!! Form::displayCountSelect($functionId, ['class'=>"col-1 form-control"]) !!}
    @endif

    <div id="{{$functionId}}-search-result">
      <div class="row mt-1">
        {!! Form::grid($functionId) !!}
      </div>
    </div>
  {{Form::close()}}
@endsection

@section('btn-footer')
  <x-auth-button kbn="update" id="{{$functionId}}-btn-update"/>
  <x-auth-button kbn="delete" id="{{$functionId}}-btn-delete" />

@endsection

@include('admin.dialog-xxx-list')

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

@push('app-style')
  <link href="{{mix('css/admin/page/sandbox.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
  <script>
    window.recommend = @json($recommend);
  </script>
  <script src="{{mix('js/admin/page/sandbox.page.js')}}" defer></script>
@endpush
