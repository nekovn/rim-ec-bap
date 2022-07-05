<?php
/**
 *  在庫取り込みblade
 */
  $noFooter=true;
  $screenName = '在庫取込';
  $functionId = 'importStocks';

?>
@extends('admin.layouts.template.base-card')

{{-- 検索部 --}}
@section('main-area')
{{Form::open(['class'=>'', 'id'=>"form-{$functionId}-upload" ,'files' => true])}}
  <div class="row">
    <div class="col-12 m-3">
      <div class="form-group row">
          <div class="col-12">
            {{Form::label('upload_data', '在庫実績CSVファイルを選択して下さい。')}}
            <div class="col-6">
              {{Form::file('upload_data', ['id'=>'upload_data', 'class'=>'required custom-file-input', 'type'=>'file'])}}
              <label class="custom-file-label" for="upload_data" data-browse="ファイルを選択"></label>
            </div>
          </div>
      </div>
      <div>
        {!! Form::uploadButton(['id' => $functionId.'_upload_btn', 'class' => 'w-auto'], 'アップロード') !!}
      </div>
    </div>
  </div>
{!!Form::close()!!}
@endsection

{{-- 処理状況 --}}
@section('main-after')
  <div class="card ">
        <div class="card-body">
            @include('admin.parts.import-list-parts')
        </div>
  </div>
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

@push('app-style')
    <link href="{{mix('css/admin/page/import.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{mix('js/admin/page/import.stocks.page.js')}}" defer></script>
@endpush
