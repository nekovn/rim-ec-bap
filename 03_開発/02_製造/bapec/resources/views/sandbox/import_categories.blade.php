<?php
/**
 *  カテゴリマスタ取り込みblade
 */
  $screenName = 'カテゴリマスタ取り込み';
  $functionId = 'importCategories';
  $attributes=['id'=>$functionId];
?>
@extends('admin.layouts.template.base-card')

{{-- 検索部 --}}
@section('main-area')
{{Form::open(['class'=>'', 'id'=>"form-{$functionId}-upload" ,'files' => true])}}
  <div class="row">
    <div class="col-12 m-3">
      <div class="form-group row">
          <div class="col-12">
            {{Form::label('upload_data', 'カテゴリマスタCSVファイルを選択して下さい。')}}
            <div class="col-6">
              {{Form::file('upload_data', ['id'=>'upload_data', 'class'=>'required custom-file-input', 'type'=>'file'])}}
              <label class="custom-file-label" for="upload_data" data-browse="ファイルを選択"></label>
            </div>
            <p class="text-danger">※カテゴリは差分更新は行えません。常に全件ファイルを選択してください。</p>
          </div>
      </div>
    </div>
  </div>
{!!Form::close()!!}
@endsection

@section('btn-footer')
{!! Form::uploadButton(['id' => $functionId.'_upload_btn', 'class' => 'w-auto'], 'アップロード') !!}
{!! Form::downloadButton(['id' => $functionId.'_download_btn', 'class' => 'w-auto'], 'ダウンロード') !!}
<input type="hidden" id="btn-download-exec">
@endsection

{{-- 処理状況 --}}
@section('main-after')
<div class="row ml-2 mr-2">
  <div class="col-md-12 form-inline">
      <h5 class="">処理状態</h5>
      <div class="text-muted ml-3">
          ※直近5件を表示しています
      </div>
  </div>
</div>
{{-- 検索結果 --}}
<div id="{{ $attributes['id']}}-search-result" class="ml-1 mr-1">
  <div class="row mt-1">
      <div id="total-count" class="col-12 text-right"></div>
      {!! Form::grid($attributes['id']) !!}
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
    <script src="{{mix('js/admin/page/importCategories.page.js')}}" defer></script>
@endpush
