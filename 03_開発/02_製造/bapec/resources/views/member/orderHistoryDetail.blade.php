@php
$screenName = "購入履歴詳細";

use App\Enums\OrderStatusTypeDefine;
@endphp
@extends('member.layouts.app')

@section('breadcrumb')
@endsection

@section('content')
<div id="mypage">
    <section class="sec01">
      <h2>PURCHASE HISTORY</h2>
      <p class="jp">購入履歴詳細</p>

      <p class="name"><span class="name01">{{ Auth::user()->surname }}</span> <span class="name02">{{ Auth::user()->name }}</span> 様</p>
      <div class="box_history">
        @if ( $order->status == OrderStatusTypeDefine::WAITING )
          <p class="settlement-text">決済が完了しておりません。こちらから決済手続きをお願いします。</p>
          <div class="btn_area settlement">
            <input class="button left" onClick="location.href='{{ $order->orderPayment->payment_url }}'" value="決済手続き">
            {{Form::open(['id' => 'form-order-history-detail', 'url' => route('order.cancel', $order->id), 'method' => 'post'])}}
              @method('PUT')
              <input class="button right" id="order-history-detail-btn-cancel" value="注文キャンセル">
            {{Form::close()}}
          </div>
        @endif
        <ul class="list_detail">
          <li>購入日時：{{ $order->ordered_at }}</li>
          <li>注文番号：{{ $order->id }}</li>
          <li>合計金額：{{ V::priceWithSymbol( $order->total ) }}（税込）</li>
          <li>状況：{{ $order->getOrderStatusValueAttribute() }}</li>
        </ul>
        @foreach( $order->orderDetails as $orderdetail )
          <ul class="list_item wide">
            <li>
              <figure>
              @if(isset($orderdetail->goods->image_url))
                <img src="{{ $orderdetail->goods->image_url }}" alt="" class="img-load-chk">
              @else
                <div class="no-image"><div class="no-image-inner"></div></div>
              @endif
              </figure>
              <div>
                <p>商品名：{{ $orderdetail->name }}</p>
                <p>規格：{{ $orderdetail->volume }}</p>
                <p>金額：{{ V::priceWithSymbol( $orderdetail->sale_price_tax_included ) }}
                  × {{ $orderdetail->quantity }}
                  ＝ {{ V::priceWithSymbol( $orderdetail->subtotal_tax_included ) }}（税込）</p>
              </div>
            </li>
          </ul>
        @endforeach
        @php
          $orderdelivery = $order->orderdeliveries[0];
        @endphp
        <table cellpadding="0" cellspacing="0" class="buyer">
          <tr>
            <th>お届け先</th>
            <td>
              氏名：{{ $orderdelivery->delivery_surname }} {{ $orderdelivery->delivery_name }}<br>
              郵便番号：{{ V::zipFormatWithSymbol( $orderdelivery->delivery_zip ) }}<br>
              住所：{{ $orderdelivery->delivery_addr }}<br>
              電話番号：{{ $orderdelivery->delivery_tel }}
            </td>
          </tr>
          @if($orderdelivery->carrier)
          <tr>
            <th>配送業者</th>
            <td>{{ $orderdelivery->carrier->name }}</td>
          </tr>
          @endif
          <tr>
            <th>お届け希望日</th>
            <td>
              {{ $orderdelivery['delivery_date'] ? $orderdelivery['delivery_date']->format('Y年n月j日') : '指定なし' }}
            </td>
          </tr>
          <tr>
            <th>お届け希望時間</th>
            <td>{{ $orderdelivery->getDeliveryTimeNameAttribute()?: '指定なし' }}</td>
          </tr>
          <tr>
            <th>支払方法</th>
            <td>{{ $order->getPaymentMethodValueAttribute() }}</td>
          </tr>
          <tr>
            <th>注文コメント</th>
            <td>{!! nl2br(e($order->comment)) !!}</td>
          </tr>
          <tr>
            <th>商品合計</th>
            <td>{{ V::priceWithSymbol( $order->goods_total_tax_included ) }}-（税込）</td>
          </tr>
          <tr>
            <th>送料</th>
            <td>{{ App\Helpers\Blade\Helper::priceWithSymbol( $order->postage_total ) }}-（税込）</td>
          </tr>
          <tr>
            <th>決済手数料</th>
            <td>{{ V::priceWithSymbol( $order->payment_fee_total ) }}-（税込）</td>
          </tr>
          <tr>
            <th>利用ポイント</th>
            <td>{{ number_format($order->used_point) }}</td>
          </tr>
          <tr>
            <th>合計金額</th>
            <td>{{ V::priceWithSymbol( $order->total ) }}-（税込）</td>
          </tr>
        </table>
      </div>
      <div class="btn_area">
        <input class="button left link" onClick="location.href='{{ route('order.history') }}';" value="一覧に戻る">
        <input class="button right link" onClick="location.href='{{ route('members.home') }}';" value="マイページトップに戻る">
      </div>
    </section>
</div>
@endsection

@section('information')
  @include('member.layouts.information')
@endsection

@section('sidenav')
  @include('member.layouts.sidenav')
@endsection

@push('app-script')
  <script src="{{mix('js/member/page/order.history.detail.page.js')}}" defer></script>
@endpush
