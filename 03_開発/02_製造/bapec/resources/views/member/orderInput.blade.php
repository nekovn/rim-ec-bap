@php
$screenName = "注文情報入力";
$functionId = 'order-input';
//$customerId        =  Form::hidden('id', $order->customer_id); //顧客ID 隠し
//$shippingAddress   =  Form::hidden('address', $order->customer_address); //配送先選択　隠し
$customerId      = $order->customer_id;
$shippingAddress = $order->customer_address;
$point =  Auth::user()->remainingPoints();
use App\Enums\PaymentMethodDefine;
use App\Enums\CodeDefine;

@endphp
@extends('member.layouts.app')

@section('breadcrumb')
@endsection

@section('content')
<div id="order">
    <section class="sec01">
      <h2>SHOPPING CART</h2>
      <p class="jp">{{ $screenName }}</p>

      {{ session('checkout_error') }}
      @if ($errors->any())
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-triangle"></i>
            {{ \Lang::get('messages.E.validation') }}
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      @endif
      {{ Form::open(['route'=>'order.confirm', 'method'=>'POST', 'name'=>'', 'id'=>'form-'.$functionId, 'class'=>'']) }}
        <div class="box_order">
          <dl class="single">
            <dt>ご注文者様情報</dt>
            <dd>
              <ul class="orderer">
                {{ Form::hidden('id', '$customerId') }}
                <li>郵便番号：{{ V::zipFormatWithSymbol($order->zip) }}</li>
                <li>住所：{{ $order->addr }}</li>
                <li>氏名：{{ $order->surname.$order->name }} 様</li>
                <li>電話番号：{{ $order->tel }}</li>
              </ul>
            </dd>
          </dl>
          {{ Form::hidden('address', '$shippingAddress') }}
          {!! Form::hidden('delivery_type', '1'); !!}
          @if ($items['deliveryDateDisplayFlg'])
            <dl class="double">
              <dt>お届け希望予定日</dt>
              <dd>
                {!! Form::select('delivery_date', $items['deliveryDate'],@$orderDelivery['delivery_date'] ? $orderDelivery['delivery_date']->format('Y-m-d') : null, ['class'=> 'schedule']) !!}
              </dd>
            </dl>
          @else
            {{Form::hidden('delivery_date', '')}}
          @endif
          @if ($items['deliveryTimeDisplayFlg'])
            <dl class="double right">
              <dt>お届け希望時間</dt>
              <dd>
                {{-- {!! Form::select('tidelivery_timeme', $items['deliveryTime'], @$selected['deliveryTime'], ['class'=> 'schedule']) !!} --}}
                {{Form::dropdown('delivery_time'
                  , SystemHelper::getCodes(CodeDefine::DELIVERY_TIME), $orderDelivery['delivery_time'],['class'=> 'schedule']
                )}}
              </dd>
            </dl>
          @endif
          <dl class="single">
            <dt>お支払い方法</dt>
            <dd>
              <ul class="radio">
                @php
                  $element = "";
                  $index = 0;
                  foreach ($settlements  as $payment){
                    $isChecked = $payment->code == $order->payment_method ? true : '';
                    switch($payment->code) {
                      case PaymentMethodDefine::CASH_ON_DELIVERY:
                        $id = '-cash';
                        $class = 'charge';
                        break;
                      case PaymentMethodDefine::NO_CHARGE:
                        $id = '-no-charge';
                        $class = 'no-charge';
                        break;
                      default:
                        $id = '-card-'.$payment->id;
                        $class = 'charge';
                        break;
                    }

                    $element.= "<li id='$functionId$id' class='$class'>";

                    if($index == 0) {
                      // 最初の要素に必須チェックを追加
                      $element.= Form::radio('payment', $payment->id , $isChecked,
                                            ['id' => 'pay'.$payment->id, 'data-payment-method' => $payment->code,
                                             'required', 'data-parsley-errors-container' => '#error_payment']);
                    } else {
                      $element.= Form::radio('payment', $payment->id , $isChecked, ['id' => 'pay'.$payment->id, 'data-payment-method' => $payment->code]);
                    }
                    $element.= Form::labelEx('pay'.$payment->id, $payment->display_name, ['class' => 'radio_css']);

                    $element.= Form::hidden('id', $payment->id);
                    $element.= Form::hidden('upper_limit', $payment->upper_limit);
                    $element.= Form::hidden('lower_limit', $payment->lower_limit);
                    $element.= "</li>";
                    $index++;
                  }
                @endphp
                {!! $element !!}
                <div id="error_payment"></div>
              </ul>
            </dd>
          </dl>
          @if(!is_null($items['salon']))
          <dl class="double">
            <dt>サロン</dt>
            <dd>
              <select id="{{ $functionId.'-salon'}}" name="bcrews_salon_id" class="schedule" required>
                <option value="">-</option>
                @foreach ($items['salon']  as $salon)
                  <option value="{{$salon['salon_id']}}" data-name="{{$salon['salon_name']}}" data-sname="{{$salon['salon_short_name']}}"
                    {{$salon['salon_id'] == $order['bcrews_salon_id'] ? 'selected' :''}}>{{$salon['salon_name']}}
                  </option>
                @endforeach
              </select>
              <input type="hidden" id="bcrews_salon_name" name="bcrews_salon_name" value="">
              <input type="hidden" id="bcrews_salon_short_name" name="bcrews_salon_short_name" value="">
            </dd>
          </dl>
          <dl class="double right">
            <dt>スタッフ</dt>
            <dd>
              <select id="{{ $functionId.'-staff'}}" name="bcrews_staff_id" class="schedule" required>
                <option value="">-</option>
                @foreach ($items['salon']  as $salon)
                  @foreach ($salon['salon_staffs']  as $staff)
                    <option value="{{$staff['staff_id']}}"
                      data-salonid="{{$salon['salon_id']}}"
                      data-name="{{$staff['staff_name']}}"
                      class="{{ $salon['salon_id']== $order['bcrews_salon_id'] ? '' :'d-none'}}"
                      {{$staff['staff_id']== $order['bcrews_staff_id'] ? 'selected' :''}}>{{$staff['staff_name']}}</option>
                  @endforeach
                @endforeach
              </select>
              <input type="hidden" id="bcrews_staff_name" name="bcrews_staff_name" value="">
            </dd>
          </dl>
          @endif
          <dl class="point">
            <dt>ポイント利用</dt>
            <dd>
              {!! Form::text('point', $order->used_point, ['id' => $functionId.'-point']) !!}
              <span>pt</span>
              {!! Form::button('再計算', ['id' => $functionId.'-recalculation' ,'value' => 0 ]) !!}
              <p id="{{ $functionId.'-owned-point'}}" data-origin="{{ $point }}" data-point="{{ $point }}">保有ポイント：{{  $point }}pt</p>
            </dd>
          </dl>
          <dl class="single">
            <dt>注文コメント</dt>
            <dd>{!! Form::textarea('comment', $order['comment'], [
                  'class'     => '',
                  'name'      => 'comment',
                  'id'        => 'comment',
                  'maxlength' =>'2000',
                  ]) !!}
            </dd>
            <dt>{!! Form::labelEx('payment_information', 'お支払い情報') !!}</dt>
            <dd>
              <dl>
                <dt>商品合計（税込）</dt>
                <dd>￥{{number_format($order->goods_total_tax_included)}}</dd>
                <dt>送料</dt>
                <dd id="{{ $functionId.'-postage_total' }}">￥{{number_format($order->postage_total)}}</dd>
                @php
                  $feeStyle = "none";
                  if(@$order['payment_method'] == PaymentMethodDefine::CASH_ON_DELIVERY) {
                    $feeStyle = "block";
                  }
                @endphp
                <div id="{{ $functionId.'-show-cash' }}" style="display:{{ $feeStyle }}">
                  <dt>決済手数料</dt>
                  <dd id="{{ $functionId.'-payment-cash' }}">￥{{number_format($order['payment_fee_total'])}}</dd>
                </div>
                <dt>合計（税込）</dt>
                <dd id="{{ $functionId.'-totalTaxIncluded' }}" data-total="{{ number_format($order->total) }}" >￥{{number_format($order->total)}}</dd>
              </dl>
            </dd>
          </dl>
        </div>
        <div class="btn_area">
          {!! Form::button('カートに戻る',['class' => 'button left', 'id' => $functionId.'-btn-cart']) !!}
          {!! Form::submit('確認',['class' => 'button right', 'id' => $functionId.'-btn-confirm']) !!}
        </div>
      {{Form::close()}}
    </section>
</div>
@endsection

@section('information')
  @include('member.layouts.information')
@endsection

@section('sidenav')
  @include('member.layouts.sidenav')
@endsection

@push('app-style')
@endpush

@push('app-script')
  <script src="{{mix('js/member/page/order.input.page.js')}}" defer></script>
@endpush
