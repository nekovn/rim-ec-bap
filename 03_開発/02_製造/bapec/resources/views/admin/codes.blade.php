<?php
/**
 *  コードマスタblade
 */
$screenName = 'コードマスタ';
$functionId = 'codes';
?>
@extends('admin.layouts.template.simple-crud')

{{-- 検索部 --}}
@section('search-condition')
			<div class="form-group ">
                {{Form::label('search-input-code', 'コード', ['class'=>'mr-2'])}}
                {{Form::text('search-input-code', null,
                    ['class'=>'form-control',
                     'size'=>'2',
                     'maxlength'=>'3',
                     'data-parsley-type'=>'alphanum',
                     'data-parsley-trigger'=>'keyup focusout change input'
                    ])
                }}
            </div>
            <div class="form-group ml-1">
                {{Form::select('search-select-code', $selections->pluck('code_name', 'code'), null, 
                    ['class'=>'form-control',
                     'id'=>'search-select-code',
                     'placeholder'=>'選択してください',
                     'required'
                    ])
                }}
            </div>
@endsection

{{-- 編集部分 --}}
@section('detail')
    <div class="row">
        <div class="col-md-10 pl-3">
            <div class="form-group">
                {!!Form::label('code','コード',['class'=>'required'])!!}
                <div class="form-inline">
                    {{Form::text('input-code', null,
                        ['class'=>'form-control',
                         'id'=>'input-code',
                         'size'=>'2',
                         'maxlength'=>'3',
                         'data-parsley-type'=>'alphanum',
                         'data-parsley-trigger'=>'keyup focusout change input'
                        ])
                    }}
                    {{Form::select('code', $selections->pluck('code_name', 'code'), null, 
                        ['class'=>'form-control ml-1',
                         'id'=>'code',
                         'placeholder'=>'選択してください',
                         'required'
                        ])
                    }}
                </div>
            </div>
            <div class="form-group">
                {!!Form::label('value', '値 (100文字以下)',['class'=>'required'])!!}
                {{Form::text('value', null,
                    ['class'=>'form-control',
                     'required',
                     'maxlength'=>'100'
                    ])
                }}
            </div>
            <div class="form-group ">
                {{Form::label('description', '内容 (100文字以下)')}}
                {{Form::text('description', null, ['class'=>'form-control', 'maxlength'=>'100'])}}
            </div>
            <div class="form-group ">
                {{Form::label('remark', '備考')}}
                {{Form::text('remark', null, ['class'=>'form-control', 'maxlength'=>'200'])}}
            </div>
            <div class="row form-group">
                <div class="col-6">
                    {{Form::label('attr_1_description', '属性1説明')}}
                    {{Form::text('attr_1_description', null, ['class'=>'form-control col-10', 'maxlength'=>'200'])}}
                </div>
                <div class="col-6">
                    {{Form::label('attr_1', '属性1')}}
                    {{Form::text('attr_1', null, ['class'=>'form-control col-10', 'maxlength'=>'200'])}}
                </div>
            </div>
            <div class="row form-group">
                <div class="col-6">
                    {{Form::label('attr_2_description', '属性2説明')}}
                    {{Form::text('attr_2_description', null, ['class'=>'form-control col-10', 'maxlength'=>'200'])}}
                </div>
                <div class="col-6">
                    {{Form::label('attr_2', '属性2')}}
                    {{Form::text('attr_2', null, ['class'=>'form-control col-10', 'maxlength'=>'200'])}}
                </div>
            </div>
            <div class="row form-group">
                <div class="col-6">
                    {{Form::label('attr_3_description', '属性3説明')}}
                    {{Form::text('attr_3_description', null, ['class'=>'form-control col-10', 'maxlength'=>'200'])}}
                </div>
                <div class="col-6">
                    {{Form::label('attr_3', '属性3')}}
                    {{Form::text('attr_3', null, ['class'=>'form-control col-10', 'maxlength'=>'200'])}}
                </div>
            </div>
            <div class="form-group">
                {!!Form::label('sequence', '表示順')!!}
                {{Form::number('sequence', 100,
                    ['class'=>'form-control w-25',
                     'min'=>1,
                     'max'=>999
                    ])
                }}
            </div>
        </div>
    </div>
@endsection

@push('app-style')
    <link href="{{mix('css/admin/page/codes.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{mix('js/admin/page/codes.page.js')}}" defer></script>
@endpush
