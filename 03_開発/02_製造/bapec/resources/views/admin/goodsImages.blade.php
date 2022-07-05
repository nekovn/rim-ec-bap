@php
  /**
   * 商品画像マスタblade
   */
$screenName = '商品サムネイル登録';
$functionId = 'goods-images';
@endphp
@extends('admin.layouts.app')

@section('content')
  <div class="container-fluid">
    {{Form::open(['id' =>'form-'.$functionId, 'class'=>'p-3', 'files'=>true, 'data-parsley-validate'])}}
    <div class="card">
      <div class="card-header detail-title">
          <b>{{$screenName}}</b>
      </div>
      <div class="card-body pt-0">
        <div class="btn-header">
          {!! Form::backButton(['id' => 'btn-back', 'class' => 'btn-sm']) !!}
        </div>
        <div id="thumbnail_list">

          <div class="row m-2 p-0 card shadow bg-white rounded d-none" id="thumbnail_:id">
            <div class="row no-gutters">
              <div class="col-md-1 d-flex align-items-center">
                <span class="display_order_dsp"></span>
                {{Form::hidden('display_order_:id', 0, ['class'=>'form-control display_order'])}}
              </div>
              <div class="col-md-11 p-2 row">
                <div class="col-5 image_preview_col">
                  <div class="image_preview_frm">
                    <img src="" id="image_preview_:id" class="image_preview" alt="プレビュー">
                  </div>
                </div>
                <div class="col-7 p-2">
                  <div class="row upload_image_col">
                    {{Form::file('upload_image_:id', [
                      'id'=>'upload_image_:id',
                      'class'=>'upload_image custom-file-input form-control',
                      'type'=>'file',
                      'data-id'=>':id'
                    ])}}
                    <label class="custom-file-label" for="upload_image_:id" data-browse="画像を選択"></label>
                    {{Form::textEx('image_:id', null, ['id'=>'image_:id', 'readonly', 'class'=>'image d-none'])}}
                  </div>
                  <div class="row mt-5 display_order_col">
                    <div class="ml-auto btn_delete_col">
                      <i class="btn far fa-trash-alt fa-lg btn-delete" id="btn-delete_:id" data-id=':id'></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="row btn-footer">
          <button type="button" class="btn btn-info" id="btn-store">
            <i class="far fa-edit"></i>
            登録
          </button>
        </div>
      </div>
    </div>
    {{Form::close()}}
  </div>
@endsection
@push('head-style')
  <link href="{{mix('css/admin/page/goods.images.page.css')}}" rel="stylesheet">
@endpush
@push('head-script')
  <script>
    window.goods_id = {{$goods_id}};
  </script>
  <script src="{{mix('js/admin/page/goods.images.page.js')}}" defer></script>
@endpush
