
<?php
use App\Enums\CodeDefine;
use App\Enums\StatusDefine;
use App\Models\CustomerRank;

/**
 *  受注詳細blade
 */
$screenName = '受注詳細';
$functionId = 'order-details';
/*-- 受付状態の時のみ */
$disabled = 'disabled';
if ( $order->status == StatusDefine::UKETSUKE || $order->status == StatusDefine::KESSAI_MACHI) {
    $disabled = '';
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
        {{Form::open(['id' =>'form-'.$functionId, 'data-olddt'=> $order->updated_at ,'data-id'=> $order->id ])}}
        <div class="row mt-2 ml-2">
            <div class="col-md-4">
                <div class="d-flex"><dt class=" " >受注ID</dt>
                    <dd class="ml-2">{{$order->id}}</dd></div>
            </div>
            <div class="col-md-4">
                <div class="d-flex"><dt class=" ">受注日時</dt>
                    <dd class="ml-2">{{$order->ordered_at}}</dd></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <div class="card ">
                    <div class="card-body">
                        <div class="row justify-content-around">
                            <div class="col-md-5">
                                <dt class=" ">ご注文者様情報</dt>
                                <dd >{{ V::zipFormatWithSymbol($order->zip) }}</dd>
                                <dd >{{$order->addr}}</dd>
                                <dd >{{$order->surname}} {{$order->name}}
                                    <span class=" small">{{ '('. $order->surname_kana . ' ' . $order->name_kana . ')'}}</span>
                                </dd>
                                
                                <dd >{{'TEL ' . $order->tel}}</dd>
                                <dd >会員ランク：{{CustomerRank::find($order->customer_rank_id)->rank_name}}</dd>
                            </div>
                            <div class="col-md-3 ">
                                <div class="row">
                                <div class="col-md-6">
                                    <dt class=" ">注文コメント</dt>
                                    <dd>{!! nl2br(e($order->comment)) !!}</dd>
                                </div>
                                <div class="col-md-6">
                                    
                                </div>
                                
                                </div>
                            </div>
                             <div class="col-md-3 ">
                                 <dt class=" ">ステータス</dt>
                                    <dd >
                                        {{Form::dropdown('status'
                                            , $payment_status_list, $order->status, [$disabled, 'class'=>'input-caution'])}}
                                    </dd>
                                    <div class="alert alert-warning d-none small" id="status-warning">
                                        {!!__('messages.W.select.cancelstatus')!!}
                                    </div>
                                    </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>

        {{-- 配送毎 --}}
        @foreach ($details as $index => $delivery)
        <div class="card">
            <div class="card-header bg-light">
                お届け先{{ count($details)==1 ? '' : $index+1}}
            </div>
            <div class="card-body">
                <div class="row justify-content-around">
                    <div class="col-md-5 ">
                        <dt class=" ">配送先情報</dt>
                        <dd >{{ V::zipFormatWithSymbol($delivery->delivery_zip, true) }}</dd>
                        <dd >{{$delivery->delivery_addr}}</dd>
                        <dd >{{$delivery->delivery_surname . ' ' . $delivery->delivery_name }}
                            <span class=" small">{{ '('. $delivery->delivery_surname_kana . ' ' . $delivery->delivery_name_kana . ')'}}</span>
                        </dd>
                        <dd >{{'TEL ' . $delivery->delivery_tel }} </dd>
                    </div>
                    <div class="col-md-3">
                        <dt class=" ">配送希望日</dt>
                        <dd class="">{{ $delivery->delivery_date ? $delivery->delivery_date->format('Y-m-d') : '' }}</dd>
                        <dt class=" ">配送希望時間帯</dt>
                        <dd class="">{{ $delivery->delivery_time_name }}</dd>
                    </div>
                    <div class="col-md-3"></div>
                </div>
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
                                    @foreach  ($delivery->OrderDetails as $data) 
                                        <tr>
                                            <td>{{$data->goods_code}}</td>
                                            <td>{{$data->name . '/' . $data->volume}}</td>
                                            <td class="text-right">{{number_format($data->sale_price_tax_included)}}</td>
                                            <td class="text-right">{{number_format($data->quantity)}}</td>
                                            <td class="text-right">{{number_format($data->subtotal_tax_included)}}</td>
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
                        <dd><textarea rows="2" class="w-100" data-deliveryid="{{ $delivery->id }}" name="warehouse_comment" {{ $disabled }} >{{ $delivery->warehouse_comment}}</textarea></dd>
                    </div>
                   <div class="col-md-6">
                        <dt class=" ">送り状コメント</dt>
                        <dd><textarea rows="2" class="w-100" data-deliveryid="{{ $delivery->id }}" name="invoice_comment" {{ $disabled }}>{{ $delivery->invoice_comment}}</textarea></dd>
                    </div>
                    
                </div>
            </div>
        </div>
        @endforeach
        {{--  --}}
            
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-around ">
                    <div class="col-md-3">
                        <dt class="">決済方法</dt>
                        <dd class="">
                            {{  $order->settlement ? $order->settlement->display_name : ''}}
                            <div id="payment-status" data-payment-method-name="{{ $order->settlement->display_name }}" data-payment-status-name="{{ $order->OrderPayment->payment_status_name }}"
                                 class="text-center badge-status {{ SystemHelper::getCodeAttrs(CodeDefine::PAYMENT_STATUS)[$order->orderPayment()->first()->payment_status]['attr5']}} ">
                            {{ $order->OrderPayment->payment_status_name}}
                            </div>
                        </dd>
                        @if ($order->OrderPayment->transaction_id)
                          <dt class="">オーダーID</dt>
                          <dd class="">
                            {{  $order->OrderPayment->transaction_id }}
                          </dd>
                        @endif
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col-md-6 goods-amount">
                        <div class="d-flex" style="font-size:0.8rem;"><dd class="title">商品合計（税込）</dd>
                            <dd class="amount">{{ V::priceWithSymbol( $order->goods_total_tax_included) }}</dd></div>
                        <div class="d-flex" style="font-size:0.8rem;"><dd class="title">送料</dd>
                            <dd class="amount">{{ V::priceWithSymbol( $order->postage_total) }}</dd></div>
                        <div class="d-flex" style="font-size:0.8rem;"><dd class="title">決済手数料</dd>
                            <dd class="amount">{{ V::priceWithSymbol( $order->payment_fee_total) }}</dd></div>
                        <div class="d-flex" style="font-size:0.8rem;"><dd class="title">ご利用ポイント</dd>
                            <dd class="amount">{{ number_format( $order->used_point) }}pt</dd></div>
                        <div class="d-flex bg-light"><dt class="title">ご注文合計</dt>
                            <dd class="amount">{{ V::priceWithSymbol($order->total) }}</dd></div>
                        <div></div>
                        <div class="d-flex p-1 pt-2" ><dd class="title">今回獲得予定ポイント</dd>
                            <dd class="amount">{{ number_format( $order->earned_points) }}pt</dd></div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row ">
                     <div class="col-md-6">
                        <dt class="">管理側備考</dt>
                        <dd><textarea rows="2" class="w-100" name="remark" {{ $disabled }} >{{ $order->remark}}</textarea></dd>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="btn-footer">
        @if ( $disabled == '')  
          <x-auth-button kbn="update" id="{{$functionId}}-btn-update"/>
        @endif
        </div>
        {{Form::close()}}
    </div>
</div>
@endsection
@push('app-style')
    <link href="{{mix('css/admin/page/order.details.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{mix('js/admin/page/order.details.page.js')}}" defer></script>
@endpush