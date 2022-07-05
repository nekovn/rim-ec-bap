@php
/**
 * ショップ基本情報blade
 */
$screenName = 'ショップ基本情報登録';
$functionId = 'shopinfo';
@endphp
@extends('admin.layouts.app')

@section('content')
  <div class="container-fluid">
    {{Form::open(['id' =>'form-'.$functionId, 'class'=>'p-3', 'data-parsley-validate'])}}
    <div class="card">
      <div class="card-header detail-title">
        <b>{{$screenName}}</b>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-3">
            <div class="form-group">
              {{Form::labelEx('code', 'ショップコード', ['class'=>'required'])}}
              {{Form::textEx('code', null, ['class'=>'form-control', 'maxlength'=>'100', 'required'])}}
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              {{Form::labelEx('shop_name', 'ショップ名', ['class'=>'required'])}}
              {{Form::textEx('shop_name', null, ['class'=>'form-control', 'maxlength'=>'100', 'required'])}}
            </div>
          </div>
          <div class="col-1"></div>
          <div class="col-5">
            <div class="form-group">
              {{Form::labelEx('representative_name', '代表者名')}}
              {{Form::textEx('representative_name', null, ['class'=>'form-control', 'maxlength'=>'100'])}}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              {{Form::labelEx('company_name', '会社名')}}
              {{Form::textEx('company_name', null, ['class'=>'form-control', 'maxlength'=>'100'])}}
            </div>
          </div>
          <div class="col-1"></div>
          <div class="col-5">
            <div class="form-group">
              {{Form::labelEx('representative_email', '代表メールアドレス')}}
              {{Form::emailEx('representative_email', null, ['class'=>'form-control', 'maxlength'=>'254'])}}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              {{Form::labelEx('company_name_kana', '会社名カナ')}}
              {{Form::textEx('company_name_kana', null, ['class'=>'form-control', 'maxlength'=>'100'])}}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <div class="form-group">
              {{Form::labelEx('company_tel', '電話番号')}}
              {{Form::textTel('company_tel', null, ['class'=>'form-control'])}}
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              {{Form::labelEx('company_fax', 'FAX番号')}}
              {{Form::textTel('company_fax', null, ['class'=>'form-control'])}}
            </div>
          </div>
          <div class="col-1"></div>
          <div class="col-2">
            <div class="form-group">
              {{Form::labelEx('tax_rounding_type', '消費税端数処理区分', ['class'=>'required'])}}
              {{Form::selectEx('tax_rounding_type', $selections['roundingTypes'], null, ['required', 'placeholder'=>'未選択'])}}
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
              {{Form::labelEx('discount_rounding_type', '割引端数処理区分', ['class'=>'required'])}}
              {{Form::selectEx('discount_rounding_type', $selections['roundingTypes'], null, ['required', 'placeholder'=>'未選択'])}}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <div class="form-group">
              {{Form::labelEx('company_zip', '郵便番号')}}
              {{Form::postCode('company_zip', null, ['class'=>''],
                                ['autocomplete'=>[
                                    'selector-pref'=>'#company_pref',
                                    'selector-city'=>'#company_addr1',
                                    'selector-town'=>'#company_addr2']])}}
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              {{Form::labelEx('company_pref', '都道府県')}}
              {{Form::pref('company_pref', null, ['id'=>'company_pref', 'class'=>'form-control'])}}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              {{Form::labelEx('company_addr1', '市区町村')}}
              {{Form::textEx('company_addr1', null, ['id'=>'company_addr1', 'class'=>'form-control', 'maxlength'=>'100'])}}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              {{Form::labelEx('company_addr2', '町名・番地')}}
              {{Form::textEx('company_addr2', null, ['id'=>'company_addr2', 'class'=>'form-control', 'maxlength'=>'100'])}}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              {{Form::labelEx('company_addr3', '建物名等')}}
              {{Form::textEx('company_addr3', null, ['class'=>'form-control', 'maxlength'=>'100'])}}
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
  <link href="{{mix('css/admin/page/shopinfo.page.css')}}" rel="stylesheet">
@endpush
@push('head-script')
  <script src="{{mix('js/admin/page/shopinfo.page.js')}}" defer></script>
@endpush
