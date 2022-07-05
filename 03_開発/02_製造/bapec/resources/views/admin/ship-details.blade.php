
<?php
use App\Enums\CodeDefine;
use App\Enums\StatusDefine;


/**
 *  出荷詳細blade
 */
$screenName = '出荷詳細';
$functionId = 'ship-details';

// 出荷ステータスがキャンセルまたは返品の場合は、問合せ番号、更新ボタン、出荷メール送信ボタンを非活性にする
$disabled = '';
if (in_array($ship->status, [StatusDefine::SHUKKA_CANCEL,StatusDefine::SHUKKA_HENPIN])) {
    $disabled = 'disabled';
}
?>
@extends('admin.layouts.app')
@section('content')
<div class="card mt-2">
    <div class="card-header detail-title">
        <b>{{$screenName}}</b>
        <div class="card-header-actions">

        </div>
    </div>
    <div class="card-body pt-0">
        <div class="btn-header">
            {!! Form::backButton(['id' => $functionId.'-btn-back', 'class' => 'btn-sm']) !!}
        </div>
        {{Form::open(['id' =>'form-'.$functionId, 
        'data-olddt'=> $ship->updated_at ,'data-id'=> $ship->id ])}}
        <div class="row mt-2 ml-2">
            <div class="col-md-4">
                <div class="d-flex"><dt class=" " >出荷ID</dt>
                    <dd class="ml-2">{{$ship->id}}</dd></div>
            </div>
            <div class="col-md-4">
                <div class="d-flex"><dt class=" ">受注ID</dt>
                    <dd class="ml-2">{{$ship->order_id}}</dd></div>
            </div>
            <div class="col-md-4">
                <div class="d-flex"><dt class=" ">受注日時</dt>
                    <dd class="ml-2">{{$ship->Order->ordered_at}}</dd></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <div class="card ">
                    <div class="card-body">
                        <div class="row justify-content-around">
                            <div class="col-md-4">
                                <dt class=" ">ご注文者</dt>
                                <dd >{{'〒'.$order->zip}}</dd>
                                <dd >{{$order->addr}}</dd>
                                <dd >{{$order->surname}} {{$order->name}}
                                    <span class=" small">{{ '('. $order->surname_kana . ' ' . $order->name_kana . ')'}}</span>
                                </dd>
                                <dd >{{'TEL ' . $order->tel}}</dd>
                            </div>
                            <div class="col-md-4">
                                <dt class=" ">ステータス</dt>
                                <dd id="ship-status" class="badge badge-success badge-status">{{$ship->status_name}}
                                    {{-- {{Form::dropdown('status'
                                        , SystemHelper::getCodes(CodeDefine::SHIP_STATUS), $ship->status)}} --}}
                                </dd>
                                <dt class=" " >出荷指示日</dt>
                                <dd >{{$ship->ship_direct_date}}</dd>
                                <dt class=" ">出荷日</dt>
                                <dd class="ml-2">{{$ship->ship_date}}</dd>
                                <dt class=" ">出荷取消日</dt>
                                <dd class="ml-2">{{$ship->ship_cancel_date}}</dd>
                            </div>
                            <div class="col-md-3 ">
                                <dt class=" ">問合せ番号</dt>
                                <dd>
                                   {{Form::textAlphanum('slip_no', $ship->slip_no, ['size'=>'20', 'maxlength'=>'20', $disabled])}}

                                </dd>
                                <div>
                                    @if ($disabled == '')
                                      <x-auth-button kbn="update" id="{{$functionId}}-btn-update" />
                                    @else
                                      <x-auth-button kbn="update" id="{{$functionId}}-btn-update" disabled/>
                                    @endif
                                    <button type="button" class="btn btn-warning float-right"
                                    id="{{$functionId.'-btn-mail'}}"  {{ ($ship->slip_no && $disabled == '') ? '':'disabled'}} >
                                        <i class="far fa-envelope"></i>出荷メール送信
                                    </button>
                                </div>
                                @if($updAuth==1) 
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-outline-dark float-right"
                                            id="{{$functionId.'-btn-henpin'}}"  {{in_array($ship->status, [StatusDefine::SHUKKA_CANCEL,StatusDefine::SHUKKA_HENPIN]) ? 'disabled':''}}>
                                            　返 品　
                                        </button>
                                        <button type="button" class="btn btn-outline-dark " 
                                            id="{{$functionId.'-btn-cancel'}}"  {{in_array($ship->status, [StatusDefine::SHUKKA_CANCEL,StatusDefine::SHUKKA_HENPIN]) ? 'disabled':''}}>
                                            キャンセル
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <div class="card ">
                    <div class="card-body">
                        <div class="row justify-content-around">
                            
                            <div class="col-md-4">
                            <dt class=" ">依頼主情報</dt>
                            @if ($ship->client_addr != null)
                                <dd >{{ V::zipFormatWithSymbol($ship->client_zip) }}</dd>
                                <dd >{{$ship->client_addr}}</dd>
                                <dd >{{$ship->client_surname}} {{$ship->client_name}}
                                    <span class=" small">{{ '('. $ship->client_surname_kana . ' ' . $ship->client_name_kana . ')'}}</span>
                                </dd>
                                <dd >{{'TEL ' . $ship->client_tel}}</dd>
                            @else
                                ご注文者と同じ
                            @endif
                            </div>
                            
                            <div class="col-md-4 ">
                                <dt class=" ">出荷先情報</dt>
                                <dd >{{ V::zipFormatWithSymbol($ship->ship_zip) }}</dd>
                                <dd >{{$ship->ship_addr}}</dd>
                                <dd >{{$ship->ship_surname}} {{$ship->ship_name}}
                                    <span class=" small">{{ '('. $ship->ship_surname_kana . ' ' . $ship->ship_name_kana . ')'}}</span>
                                </dd>
                                <dd >{{'TEL ' . $ship->ship_tel}}</dd>
                            </div>
                            <div class="col-md-3">
                                <dt class=" ">配送希望日</dt>
                                <dd class="">{{ $ship->desired_delivery_date?: '指定なし' }}</dd>
                                <dt class=" ">配送希望時間帯</dt>
                                <dd class="">{{ $ship->desired_delivery_timename?: '指定なし' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- 出荷明細毎 --}}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <dt class=" ">商品明細</dt>
                        <div>
                            <table class="table table-responsive-sm table-sm table-bordered">
                                <thead class="thead-dark">
                                    <th>商品コード</th>
                                    <th>商品名/規格</th>
                                    <th>価格</th>
                                    <th>数量</th>
                                    <th>小計</th>
                                </thead>
                                <tbody>
                                    @foreach ($ship->ShipDetails as $index => $detail)
                                        <tr>
                                            <td>{{$detail->goods_code}}</td>
                                            <td>{{$detail->name . '/' . $detail->volume}}</td>
                                            <td class="text-right">{{number_format($detail->sale_price_tax_included)}}</td>
                                            <td class="text-right">{{number_format($detail->quantity)}}</td>
                                            <td class="text-right">{{number_format($detail->subtotal_tax_included)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row d-none">
                    <div class="col-md-6">
                        <dt class=" ">倉庫向けコメント</dt>
                        <dd><div class="card"><div class="card-body">{!! nl2br(e($ship->warehouse_comment)) !!}</div>
                        </div></dd>
                    </div>
                   <div class="col-md-6">
                        <dt class=" ">送り状コメント</dt>
                        <dd><div class="card">
                            <div class="card-body">{!! nl2br(e($ship->invoice_comment)) !!}</div>
                        </div></dd>
                    </div>
                    
                </div>
            </div>
        </div>
   
        {{--  --}}
            
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-around ">
                    <div class="col-md-4"></div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4 goods-amount">
                        
                        <div class="d-flex" style="font-size:0.8rem;"><dd class="title">送料</dd>
                            <dd class="amount">{{ V::priceWithSymbol( $ship->postage) }}</dd></div>
                        <div class="d-flex" style="font-size:0.8rem;"><dd class="title">決済手数料</dd>
                            <dd class="amount">{{ V::priceWithSymbol( $ship->payment_fee) }}</dd></div>
                        <div class="d-flex" style="font-size:0.8rem;"><dd class="title">梱包料</dd>
                            <dd class="amount">{{ V::priceWithSymbol( $ship->packing_charge) }}</dd></div>
                        <div></div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        {{Form::close()}}
    </div>
</div>
@endsection
@push('app-style')
    <link href="{{mix('css/admin/page/ship.details.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{mix('js/admin/page/ship.details.page.js')}}" defer></script>
@endpush