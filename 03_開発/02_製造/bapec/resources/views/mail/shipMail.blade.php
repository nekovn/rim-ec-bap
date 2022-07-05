<?php
    $order = $ships->Order;
?>
----------------------------------------------------------------
このメールはお客様の注文に関する大切なメールです。
お取引が完了するまで保存してください。
----------------------------------------------------------------
{{ $order->surname . ' ' . $order->name}} さま

ご注文いただきました商品の発送が完了いたしましたのでご連絡申しあげます。

お荷物の配送状況を以下から確認していただけます。
お荷物伝票番号を入力して発送状況をご確認ください。

----------------------------------------------------------------
伝票番号：{{$ships->slip_no}}
下記のURLから商品の配送状況が確認出来ます。
{{$ships->carrier->reference_href . $ships->slip_no}}

----------------------------------------------------------------

発送は万全を期しておりますが、もし商品に不備や破損が合った場合、すぐにご連絡ください。
今回のご注文内容は下記の通りです。
----------------------------------------------------------------
受注番号：{{ $ships->order_id }}
注文日時：{{ $order->ordered_at }}
注文者：{{ $order->surname . ' ' . $order->name}} 様
注文者住所：{{ V::zipFormatWithSymbol($order->zip) }}
　　{{ $order->addr}}
電話：{{$order->tel}}
支払方法：{{$order->payment_method_name}}
配送日時指定：{{$ships->desired_delivery_date ?? ''}}　{{$ships->desired_delivery_time_name}}
備考：{{$order->remark}}

--------------------------------
送付先：{{$ships->ship_surname . ' ' . $ships->ship_name}} 様
　　{{ V::zipFormatWithSymbol($ships->ship_zip) . ' ' .$ships->ship_addr}}
　　(TEL){{$ships->ship_tel}}

商品：
@foreach ($ships->ShipDetails as $shipDetail)
　　{{ $shipDetail->name . ' ' . $shipDetail->volume }}
　　{{'(' . $shipDetail->goods_code . ')'}}
{{-- 　　価格 {{number_format($shipDetail->sale_price) }}円 x (個) = {{number_format($shipDetail->subtotal_tax_included)}}円 (税込) --}}
@endforeach
{{'================================================================'}}
{{-- 合計商品数   {{count($ships->ShipDetails)}}(個)
商品価格計   TODO円
--------------------------------
小計         TODO円
消費税       TODO円
ポイント利用 TODO
送料         {{number_format($ships->postage)}}円
----------------------------------------------------------------
合計        TODO円
---------------------------------------------------------------- --}}
※このメールはお客様へのお知らせ専用となっておりますですので、
　このメールにご返信いただいても回答できません。ご了承ください。

ご質問やご不明な点がございましたら、お気軽にお問い合わせください。
■お問い合わせ ： {{ $mailinquiry }}
{{-- メール設定のコード値．KEY=2に該当する、属性１値 --}}

@include('mail.signature')