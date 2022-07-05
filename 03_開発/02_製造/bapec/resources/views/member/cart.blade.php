@php
$screenName = "カート";
$functionId = 'cart';
@endphp
@extends('member.layouts.app')

@section('content')
<div id="cart">
    <section class="sec01">
      <h2>SHOPPING CART</h2>
      <p class="jp">ショッピングカート一覧</p>

      {{Form::open(['route'=>'member.cart.next', 'method'=>'POST', 'id'=>'form-'.$functionId, 'files'=>false])}}

        {{-- @if ($cartitems['items']->count()==0)  --}}
            <div class="data-empty  {{$cartitems['items']->count()==0 ? '':'d-none'}}">
            <div class="">
            現在、買い物かごには商品が入っておりません。<br>
            お買い物を続けるには下の「商品を探す」をクリックしてください。
            </div>
            <div class="btn_area ">
                <button class="button right" type="button"  onClick="location.href = '/'">商品を探す</button>
            </div>
            </div>
        {{-- @else  --}}
        @if ($cartitems['items']->count() > 0)

        <div class="data-on">
        <table cellpadding="0" cellspacing="0" class="table_cart" id="{{$functionId}}-item-table">
        <tbody>
        <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>規格</th>
            <th>税込単価</th>
            <th>数量</th>
            <th>小計</th>
            <th></th>
        </tr>
        @foreach ($cartitems['items'] as $rowId => $item)
            <tr class="item-row" data-rowid="{{$item->rowId}}">
            <td><img src="{{ \Storage::disk(config('app.goods_image_filesystem_driver'))->url($item->image) }}" alt="" class="img-load-chk"></td>
            <td><span class="sp">商品名：</span><span name="name">{{$item->name}}</span></td>
            <td><span class="sp">規格：</span><span name="volume">{{$item->volume}}</span></td>
            <td><span class="sp">税込単価：￥</span><span name="salePriceTaxIncluded_v">{{$item->salePriceTaxIncluded()}}</span></td>
            <td><span class="sp">数量：</span>
                <select class="quantity {{$functionId}}-item-qty" >
                @for ($idx = 1; $idx <= 100; $idx++)
                <option value="{{$idx}}" {{$item->qty==$idx ? 'selected': ''}}>{{$idx}}</option>
                @endfor
                </select>
            </td>
            <td><span class="sp">小計：￥</span><span name="subtotalTaxIncluded_v">{{$item->subtotalTaxIncluded()}}</span></td>
            <td><button type="button" class="button {{$functionId}}-btn-item-delete" >削除</button></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{-- <div class="data-on"> --}}
            <p class="total">小計：￥<span name="total">{{ $cartitems['total']}}</p>
            <p class="txt_guide">送料、手数料等については注文確認画面でご確認ください。</p>

            <div class="btn_area">
              <button class="button left data-on" type="button" id="{{$functionId}}-btn-next">購入手続きに進む</button>
              <button class="button right" type="button" id="{{$functionId}}-btn-back">お買い物を続ける</button>
            </div>
        {{-- </div> --}}
        </div>
        @endif

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

@push('app-script')
<script src="{{mix('js/member/page/cart.page.js')}}" defer></script>
@endpush
