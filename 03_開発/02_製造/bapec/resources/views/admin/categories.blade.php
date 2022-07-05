<?php
use App\Enums\CodeDefine;
use App\Enums\StatusDefine;
/**
 *  カテゴリ設定blade
 */
$screenName = 'カテゴリ設定';
$functionId = 'categories';
?>
@extends('admin.layouts.app')
@section('content')
<div class="card ">
   <div class="card-header">
        <b>{{$screenName}}</b>
        <div class="card-header-actions">
        </div>
    </div>
    <div class="card-body pt-1">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header">
                        <button type="button" class="btn btn-outline-info btn-sm " id="{{$functionId}}-btn-topcategory-create" >
                            <i class="fas fa-tree"></i>トップカテゴリ作成
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm " id="{{$functionId}}-btn-category-create" >
                            <i class="fas fa-leaf"></i>カテゴリ作成
                        </button>
                        <code>「更新」ボタンを押すとデータベースへの更新が実行されます</code>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-6 offset-md-1">
                            {{-- カテゴリツリー --}}
                            <div id="tree" class=""></div>
                        </div>
                     <div class="col-md-4">
                        <div id="{{$functionId}}-detail-area" class=" detail-area">
                        <div class="card">
                            <div class="card-body p-2">
                                
                                {{-- カテゴリ編集 --}}
                                <div class="form-group  ">
                                    {{Form::label('code', 'カテゴリコード', ['class'=>'mr-2'])}}
                                    {{Form::textDigits('code', null,['maxlength'=>100, 'disabled'])}}
                                </div>
                                <div class="form-group ">
                                    {{Form::label('name', 'カテゴリ名', ['class'=>'mr-2'])}}
                                    {{Form::textEx('name', null,['maxlength'=>'100'])}}
                                </div>
                                <div class="mt-2">
                                <button type="button" class="btn btn-outline-info btn-sm" id="{{$functionId}}-btn-category-reflect" >
                                    <i class="far fa-arrow-alt-circle-left"></i> 反　映
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm float-right" id="{{$functionId}}-btn-category-delete" >
                                    <i class="fas fa-leaf"></i>カテゴリ削除
                                </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-1 pb-1">
                    <x-auth-button kbn="update" id="{{$functionId}}-btn-update" />
                </div>
                 </div>
            </div>
           
        </div>
        {{Form::open(['id'=>'form-'.$functionId.'-searchcondition','class'=>'','files' => true])}}
            <input type="hidden" name="search-code">
        {{Form::close()}}
        {{-- 商品一覧 --}}
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>カテゴリ商品</b>
                    <div class="card-header-actions">
                        <button class="btn card-header-action btn-dark text-white" id="{{$functionId}}-btn-goods-search"><i class="fas fa-plus"></i> 商品検索</button>
                    </div>
                </div>
                <div class="card-body pt-1">
                {{-- 検索結果 --}}
                <div id="{{$functionId}}-search-result" class="d-none">
                    <div class="row mt-1">
                        {{-- Grid macroより --}}
                        {{-- -- 表示件数 limitを設定-- --}}
                        <div class="col-12 d-flex justify-content-between" id="{{$functionId.'-grid-count-area'}}">
                        <div class="tbl_length" >
                        <label class=""><span class="mr-1">表示件数</span>
                        {{-- {{ SystemHelper::getAppSettingValue('page.pagination.display-count.selects')}} --}}
                            {{$selections['limit']}}
                            <input type="hidden" id="{{$functionId.'-display-count'}}" value="{{$selections['limit']}}">
                        </label>
                        </div>
                        <div class="{{$functionId.'-grid-total-count'}} my-auto font-sm"></div>
                        </div >
                        <div id="{{$functionId.'-grid'}}" class="ag-theme-alpine grid mb-2" ></div>
                        <ul id="{{$functionId.'-grid-pagination'}}" class="pagination mt-0 mb-1"></ul>

                        {{-- {!! Form::grid($functionId) !!} --}}
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>


    </div>
</div>
@include('admin.dialog-goods-select')

@endsection

@push('app-style')
    <link href="{{asset('vendor/jstree/themes/default/style.min.css')}}" rel="stylesheet">
    <link href="{{mix('css/admin/page/categories.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{asset('vendor/jstree/jstree.js')}}"></script>
    <script src="{{asset(mix('js/admin/page/categories.page.js'))}}" defer></script>
@endpush
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