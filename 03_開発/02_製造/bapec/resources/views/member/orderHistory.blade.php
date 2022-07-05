@php
$screenName = "購入履歴一覧";

use App\Enums\OrderStatusTypeDefine;
@endphp
@extends('member.layouts.app')

@section('breadcrumb')
@endsection

@section('content')
<div id="mypage">
    <section class="sec01">
      <h2>PURCHASE HISTORY</h2>
      <p class="jp">購入履歴</p>

      <p class="name"><span class="name01">{{ Auth::user()->surname }}</span> <span class="name02">{{ Auth::user()->name }}</span> 様</p>
      <p class="result">全{{ $orderHistories->total() }}件</p>

      @foreach( $orderHistories as $order )
      <div class="box_history">
        <ul class="list_detail">
          <li>購入日時：{{ $order->ordered_at }}</li>
          <li>注文番号：{{ $order->id }}</li>
          <li>合計金額：{{ V::priceWithSymbol( $order->total ) }}(税込)</li>
          <li>状況：{{ $order->getOrderStatusValueAttribute() }}</li>
          @if ( $order->status == OrderStatusTypeDefine::WAITING )
          <li class="btn_detail settlement">
            <input class="button link" onClick="location.href='{{ $order->orderPayment->payment_url }}'" value="決済手続き">
          </li>
          @endif
        </ul>

        @foreach( $order->orderDetails as $orderDetail )
        <ul class="list_item">
          <li>
            <figure>
              @if(isset($orderDetail->goods->image_url))
                <img src="{{ $orderDetail->goods->image_url }}" alt="" class="img-load-chk">
              @else
                <div class="no-image"><div class="no-image-inner"></div></div>
              @endif
            </figure>
            <div>
              <p>商品名：{{ $orderDetail->name }}</p>
              <p>規格：{{ $orderDetail->volume }}</p>
              <p>金額：{{ V::priceWithSymbol( $orderDetail->sale_price_tax_included ) }}
                  × {{ $orderDetail->quantity }}
                  ＝ {{ V::priceWithSymbol( $orderDetail->subtotal_tax_included ) }}（税込）</p>
            </div>
          </li>
        </ul>
        @endforeach

        <div class="btn_detail">
          <input class="button link" onClick="location.href='{{ route('order.history.detail', $order->id) }}'" value="詳細を見る">
        </div>
      </div>
      @endforeach

      <div class="pager">
        <ul class="pageNav">
          {{ $orderHistories->links() }}
        </ul>
      </div>

      <div class="btn_area">
        <input class="button link" onClick="location.href='{{ route('members.home') }}';" value="マイページトップに戻る">
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
