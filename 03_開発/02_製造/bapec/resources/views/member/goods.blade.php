@php
$screenName = "商品一覧";
$functionId = 'products';
@endphp
@extends('member.layouts.app')

@section('breadcrumb')
@endsection

@section('content')
<div id="list">
    <section class="sec01">
      <h2>PRODUCT LIST</h2>
      <p class="jp">販売商品一覧</p>

      <dl class="result_area">
        <dt class="result">全{{ $goodslist->total() }}件</dt>

        {{Form::open(['route'=>'goods.search', 'method'=>'get', 'id'=>'goodslist_form', 'files'=>false])}}
        <dd>並べ替え：
          <input type="hidden" name="k" value="{{ $k }}">
          <input type="hidden" name="c" value="{{ $c }}">
          <select onchange="submit(this.form)" name="s" id="goodssort" class="sort">
            <option value="name" {{ $s=='name'? 'selected': '' }}>商品名順</option>
            <option value="new" {{ $s=='new'? 'selected': '' }}>新着順</option>
            <option value="maker" {{ $s=='maker'? 'selected': '' }}>メーカー順</option>
          </select>
        </dd>
        {{Form::close()}}
      </dl>

      @if (count($goodslist) > 0)
        <ul class="item_list clearfix">
        @foreach ($goodslist as $goods)
          <li>
            <figure>
              <a href="/goods/{{ $goods -> id }}" class="goodslist">
                <img src="{{ $goods->image_url }}" alt="" class="img-load-chk">
              </a>
            </figure>
            <div>
              <p class="name_jp">
                {{ $goods -> name }}
                @if ($goods -> volume)
                <br>{{ $goods -> volume }}
                @endif
              </p>
            </div>
            <p class="price"><span>{{ V::priceWithSymbol($goods -> getSalePriceTaxIncluded()) }}</span>-(税込)</p>
            <p class="btn"><input class="button" onClick="location.href='/goods/{{ $goods -> id }}';" value="詳細を見る"></p>
          </li>
        @endforeach
        </ul>
      @else
        <p>現在、該当する商品はございません。</p>
      @endif

      <div class="pager">
        <ul class="pageNav">
          {{ $goodslist->appends($pagenateParams)->links() }}
        </ul>
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
<script type="text/javascript" src="{{asset('vendor/jquery/jquery.matchHeight.js')}}"></script>
<script>
$(function(){
  $('.sec01 .item_list li figure').matchHeight();
});
</script>
@endpush
