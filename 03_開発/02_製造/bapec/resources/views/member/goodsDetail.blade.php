@php
$screenName = "商品詳細";
$functionId = 'products';

use App\Enums\SaleStatusDefine;
use App\Enums\StockManagementTypeDefine;
@endphp
@extends('member.layouts.app')

@section('breadcrumb')
@endsection

@section('content')
<div id="detail">
    <section class="sec01">
        <ul class="slider">
          <li class="slide-img">
            <img src="{{ $goods->image_url }}" alt="" class="img-load-chk">
          </li>
        @foreach ($goods->goodsImages as $thumbnail)
          <li class="slide-img">
            <img src="{{ $thumbnail->image_url }}" alt="" class="img-load-chk">
          </li>
        @endforeach
        </ul>

        <ul class="thumbnail clearfix">
          <li class="thumbnail-img">
            <img src="{{ $goods->image_url }}" alt="" class="img-load-chk">
          </li>
        @foreach ($goods->goodsImages as $thumbnail)
          <li class="thumbnail-img">
            <img src="{{ $thumbnail->image_url }}" alt="" class="img-load-chk">
          </li>
        @endforeach
        </ul>

        @php
          $quantity = 0;
          foreach( $goods->goodsStock as $goodsStock )
          {
            $quantity = $quantity + $goodsStock->quantity;
          }
        @endphp
        <p class="number">商品コード：{{ $goods->code }}</p>
        <div class="category">
          <ol class="category-breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
            @foreach ($goods->categoryWithHierarchy() as $clist)
              <li class="category-breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <div itemprop="item" href="#">
                  <span itemprop="name">
                    {{ $clist -> name }}
                  </span>
                  <meta itemprop="position" content="1" />
                </div>
              </li>
              @endforeach
          </ol>
        </div>
        {{--  <p class="category">カテゴリ：
          @foreach ($goods->categoryWithHierarchy() as $clist)
          @if(!($loop->first)) ＞ @endif
          {{ $clist -> name }}
        @endforeach
        </p>  --}}
        @if($goods->maker)
        <p class="brand">{{ $goods->maker->name }}</p>
        @endif
        <p class="name">{{ $goods->name }}</p>
        @if($goods->volume)
          <p class="plan">規格：{{ $goods->volume }}</p>
        @endif
        @if($goods->expiration_date_note)
          <p class="bestby">賞味期限：{{ $goods->expiration_date_note }}</p>
        @endif
        @if( ( $goods->sale_status == SaleStatusDefine::SALE) )
        @if( ( $quantity == '0'                                                     ) &&
              ( $goods->stock_management_type == StockManagementTypeDefine::ORDER ||
                $goods->stock_management_type == StockManagementTypeDefine::PLAN     )    )
          <div class="caution">
            受発注商品のため、発送までお時間をいただきます。<br>
            発送時期が分かり次第ご連絡させていただきます。<br>
          </div>
          @if($goods->estimated_delivery_date_name)
            <p class="deadline">納期目安：{{ $goods->estimated_delivery_date_name }}</p>
          @endif
          </p>
        @endif
        @endif
        <dl class="volume_area">
          <dt>
            <p class="ttl">価格</p>
            <p class="price"><span>{{ App\Helpers\Blade\Helper::priceWithSymbol($goods -> getSalePriceTaxIncluded()) }}</span>- (税込)</p>
          </dt>
          @if( ( $goods->sale_status == SaleStatusDefine::SALE) )
          @if(  ( $quantity > '0'                                                             ) ||
                ( ( $quantity == '0'                                                     ) &&
                  ( $goods->stock_management_type == StockManagementTypeDefine::ORDER ||
                    $goods->stock_management_type == StockManagementTypeDefine::PLAN     )    )    )
          <dd>
            <p class="ttl">数量選択</p>
            <select class="volume" id="qty" name="qty">
              <option value="1" selected>1</option>
              @for($cnt = 2; $cnt <= 100; $cnt++)
                <option value="{{$cnt}}">{{$cnt}}</option>
              @endfor
            </select>
          </dd>
          @endif
          @endif
        </dl>

        @if( ( $goods->sale_status != SaleStatusDefine::SALE) )
          <p class="soldout">{{ $goods->sale_status_name }}</p>
        @else

        @if( ( $quantity == '0'                                                     ) &&
              ( $goods->stock_management_type == StockManagementTypeDefine::STOCK ||
                $goods->stock_management_type == StockManagementTypeDefine::PLAN     )    )
            <p class="soldout">売り切れ</p>
        @endif
        @endif
    </section>

    <section class="sec02">
      @if($goods->description)
          <h3>商品説明</h3>
          <div class="txt">
            {!! $goods->description !!}
          </div>
      @endif
      <div class="box_share">
        <h3>SHARE</h3>
        <!-- <p class="icon"><img src="{{asset('images/products/icon_share.svg')}}" alt="SNSで共有する"></p> -->
        <div class="sns">
            <!--<a href="/"><img src="{{asset('images/products/icon_line.svg')}}" alt="LINE"></a>
            <a href="/"><img src="{{asset('images/products/icon_twitter.svg')}}" alt="twitter"></a>-->
            <a href="https://www.instagram.com/beast00825/"  target="_blank"><img src="{{asset('images/products/icon_instagram.svg')}}" alt="Instagram"></a>
        </div>
      </div>
    </section>
</div>

{{-- カート追加後の確認ダイアログ --}}
<div class="modal fade d-none" id="cartAddDialog" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-cart-in">
        <section>
          <p class="check">
            商品がカートに追加されました。
          </p>
          <div class="btn_area">
            <input class="button left" onclick="location.href='/cart'" value="購入手続きに進む">
            <input class="button right" id="cartAddDialogClose" data-dismiss="modal" value="お買い物を続ける">
          </div>
        </section>
      </div>
  </div>
</div>

@endsection

@section('information')
  @include('member.layouts.information')
@endsection

@section('sidenav')
  @include('member.layouts.sidenav')
@endsection

@section('otherarea')
@if( ( $goods->sale_status == SaleStatusDefine::SALE) )
  @if(  ( $quantity > '0'                                                             ) ||
  ( ( $quantity == '0'                                                     ) &&
    ( $goods->stock_management_type == StockManagementTypeDefine::ORDER ||
      $goods->stock_management_type == StockManagementTypeDefine::PLAN     )    )    )
  <div class="cart_box">
    <div class="cart_box_inner">
      {!! Form::button('カートに追加', ['class' => 'button link' , 'id' => 'goods-detail-btn-cartin']) !!}
      <input type="hidden" id="goods_id" name="goods_id" value="{{ $goods->id }}">
    </dd>
    </div>
  </div>
  @endif
@endif
@endsection

@push('app-script')
  <script src="{{mix('js/member/page/goods.detail.page.js')}}" defer></script>
  <script>
    $(function() {
        $(".slider").slick({
            autoplay: false,
            arrows: false,
            fade: true,
            asNavFor: ".thumbnail",
        });
        $(".thumbnail").slick({
            slidesToShow: 3,
            asNavFor: ".slider",
            focusOnSelect: true,
        });
    });
</script>
@endpush