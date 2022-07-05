<?php
?>
-------------------------------------------------------------------------
このメールは、ご登録いただいたメールアドレス宛に自動的に送信しております。
-------------------------------------------------------------------------

{{ $order->surname . ' ' . $order->name }} さま

この度は、ご注文いただき、誠にありがとうございます。

【ご注文内容】
注文番号　：　{{ $order->id }}
注文日時　：　{{ $ordered_at }}

【ご注文明細】
@foreach( $orderdetail as $orderDetail)
・[{{ $orderDetail->maker_name }}] {{ $orderDetail->name }} {{ $orderDetail->volume }} {{ $orderDetail->quantity }}個
@endforeach

【お届け先】
{{ V::zipFormatWithSymbol( $orderdelivery->delivery_zip ) }}
{{ $orderdelivery->delivery_addr }}
{{ $orderdelivery->delivery_surname }}　{{ $orderdelivery->delivery_name }} 様

配送希望日 ： {{ $orderdelivery->delivery_date ? $orderdelivery->delivery_date->format('Y/m/d') : '指定無し' }}
配送希望時間帯 ： {{ $orderdelivery->getDeliveryTimeNameAttribute() }}

【お支払い方法】
{{ $order->getPaymentMethodValueAttribute() }}

【ご注文金額】
商品合計：{{ V::priceWithSymbol( $order->goods_total_tax_included ) }}
割引額：{{ V::priceWithSymbol( $discountamount ) }}
送料：{{ V::priceWithSymbol( $order->postage_total ) }}
手数料：{{ V::priceWithSymbol( $commission ) }}
使用ポイント：{{ number_format($order->used_point) }}pt
合計：{{ V::priceWithSymbol( $order->total ) }}

【獲得予定ポイント】
{{ number_format($order->earned_points) }}pt

【備考】
{!! $order->comment !!}


-------------------------------------------------------------------------
ご注文内容はマイページからもご確認いただけます。
{{ route('members.home') }}

※このメールはお客様へのお知らせ専用となっておりますですので、
　このメールにご返信いただいても回答できません。ご了承ください。

ご質問やご不明な点がございましたら、こちらからお願いいたします。
■お問い合わせ ： {{ $mailinquiry }}

--

@include('mail.signature')
