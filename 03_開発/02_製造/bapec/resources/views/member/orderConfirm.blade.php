@php
$screenName = "注文情報確認";
$functionId = 'order-confirm';

use App\Enums\CodeDefine;

@endphp
@extends('member.layouts.app')

@section('content')
  <div id="order">
      <section class="sec01">
        <h2>SHOPPING CART</h2>
        <p class="jp">{{ $screenName }}</p>

        <div class="box_order">
          <dl class="single">
            <dt>ご注文者様情報</dt>
            <dd>
              <ul class="orderer">
                <li>郵便番号：{{ V::zipFormatWithSymbol($order->zip) }}</li>
                <li>住所：{{ $order->addr }}</li>
                <li>氏名：{{ $order->surname . '　' . $order->name }}</li>
                <li>電話番号：{{ $order->tel }}</li>
              </ul>
            </dd>
          </dl>

          @foreach  ($orderDeliveries as $orderDelivery)

          <dl class="single">
            <dt>配送先情報</dt>
            <dd>
              <ul class="orderer">
                <li>郵便番号：{{ V::zipFormatWithSymbol($orderDelivery->delivery_zip) }}</li>
                <li>住所：{{ $orderDelivery->delivery_addr }}</li>
                <li>氏名：{{ $orderDelivery->delivery_surname. '　' . $orderDelivery->delivery_name }}</li>
                <li>電話番号：{{ $orderDelivery->delivery_tel }}</li>
              </ul>
            </dd>
          </dl>
          <dl class="single">
            <dt>商品明細</dt>
            <dd>
              <table cellpadding="0" cellspacing="0" class="table_check">
                <tr>
                  <th>商品画像</th>
                  <th>商品名</th>
                  <th>規格</th>
                  <th>税込単価</th>
                  <th>数量</th>
                  <th>小計</th>
                </tr>
                @foreach  ($orderDetails as $orderDetail)
                <tr>
                  <td><img src="{{ \Storage::disk(config('app.goods_image_filesystem_driver'))->url($orderDetail->image) }}" alt=""></td>
                  <td><span class="sp">商品名：</span>{{ $orderDetail->name }}</td>
                  <td><span class="sp">規格：</span>{{ $orderDetail->volume }}</td>
                  <td><span class="sp">税込単価：</span>{{ V::priceWithSymbol($orderDetail->sale_price_tax_included) }}</td>
                  <td><span class="sp">数量：</span>{{ number_format($orderDetail->quantity) }}</td>
                  <td><span class="sp">小計：</span>{{ V::priceWithSymbol($orderDetail->subtotal_tax_included) }}</td>
                </tr>
                @endforeach
              </table>
            </dd>
          </dl>
          <dl class="double">
            <dt>お届け希望予定日</dt>
            <dd>{{ $orderDelivery->delivery_date ?$orderDelivery->delivery_date->format('Y年m月d日'): '指定無し' }}</dd>
          </dl>
          <dl class="double right">
            <dt>お届け希望時間</dt>
            @php
              $timeList = SystemHelper::getCodes(CodeDefine::DELIVERY_TIME)

            @endphp
            <dd>{{ $orderDelivery->delivery_time ? $timeList[$orderDelivery->delivery_time] : '指定無し' }}</dd>
          </dl>

          @endforeach

          <dl class="single">
            <dt>お支払い方法</dt>
            <dd>{{ $order->settlement ? $order->settlement->display_name : ''}} </dd>
          </dl>
          @if(!is_null($order->bcrews_salon_name))
          <dl class="double">
            <dt>サロン</dt>
            <dd>{{ $order->bcrews_salon_name }}</dd>
          </dl>
          @endif
          @if(!is_null($order->bcrews_staff_name))
          <dl class="double right">
            <dt>スタッフ</dt>
            <dd>{{ $order->bcrews_staff_name }}</dd>
          </dl>
          @endif
          <dl class="single">
            <dt>注文コメント</dt>
            <dd>{!! nl2br(e($order->comment)) !!}</dd>
            <dt>お支払い情報</dt>
            <dd>
              <dl class="mb">
                <dt>商品合計（税込）</dt>
                <dd>{{ V::priceWithSymbol($order->goods_total_tax_included) }}</dd>
                <dt>送料</dt>
                <dd>{{ V::priceWithSymbol($order->postage_total) }}</dd>
                <dt>決済手数料</dt>
                <dd>{{ V::priceWithSymbol($order->payment_fee_total) }}</dd>
                <dt>ご利用ポイント</dt>
                <dd>{{ number_format($order->used_point) }}Pt</dd>
                <dt>合計（税込）</dt>
                <dd>{{ V::priceWithSymbol($order->total) }}</dd>
              </dl>
              <dl>
                <dt>今回獲得予定ポイント</dt>
                <dd>{{ number_format($order->earned_points) }}Pt</dd>
              </dl>
            </dd>
          </dl>
        </div>
        {{ Form::open(['route'=>'order.checkout', 'method'=>'POST', 'name'=>'', 'id'=>'form-'.$functionId, 'class'=>'']) }}
        <div class="btn_area">
          {!! Form::button('ご注文手続きに戻る',['class' => 'button left', 'id' => $functionId.'-btn-confirm']) !!}
          {!! Form::button('注文する',['class' => 'button right', 'id' => $functionId.'-btn-checkout']) !!}

        </div>
        {{Form::close()}}
      </section>
</div>
@include('member.layouts.sidenav')
@endsection

@push('app-style')
@endpush

@push('app-script')
<script src="{{mix('js/member/page/order.confirm.page.js')}}" defer></script>
@endpush
