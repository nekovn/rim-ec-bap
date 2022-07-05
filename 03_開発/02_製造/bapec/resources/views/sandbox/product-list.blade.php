@php
$screenName = "";
@endphp
@extends('member.layouts.app')


@section('content')
<div id="main" class="container">
<section>
    <div class="float-right">
        <a href="{{ route('sandbox.carts')}}" class="btn btn-info">カートの中身： <span class="badge badge-pill badge-light" id="cart-item-count">{{$cartItemCount}}</span> </a>
    </div>
    <h1>商品一覧</h1>
    <div class="row">
            @foreach ($products as $idx => $value)
            
            <div  class="col-sm-4">
                <div class="card border-info" data-productid="">
                    <div class="card-body">
                        <h5 class="card-title" >{{$value['name']}}</h5>
                        <p class="card-text">
                            <label>単価：</label>
                            <span name="unit_price" class="font-lg text-danger">{{$value['unit_price']}}</span>
                                <span clas="font-sm" >
                                @switch($value['tax_type'])
                                    @case(1)
                                        (税込)
                                        @break
                                    @case(2)
                                        (税抜)
                                        @break
                                    @case(3)
                                        (税抜き)(非課税)
                                        @break
                                @endswitch
                                {{ $value->tax->tax_rate }}%
                                <br>
                                {{ $value->tax_kind_value }} - {{ $value->tax_type_value }}
                            </span>
                            {{-- <select class="form-control">
                                <option v-for="size in product.sizes" :value="size" v-text="size"></option>
                            </select> --}}
                        </p>
                        <p class="card-text">
                            <label>販売価格：</label>
                            <span name="unit_price" class="font-lg text-danger">{{$value->getSalePriceTaxIncluded()}}</span>
                            <span clas="font-sm" >(税込)</span>
                            {{-- <select class="form-control">
                                <option v-for="size in product.sizes" :value="size" v-text="size"></option>
                            </select> --}}
                        </p>
                        <p class="card-text">
                            <label>個数：</label>

                            <input type="number" class="form-control" min="0" value="0" id="qty-{{$value['id']}}">
                        </p>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-info" onClick="addCart({{$value['id']}})">カートへ入れる</button>
                    </div>
                </div>
                <br>
            </div>
        @endforeach
    </div>
</section>
</div>

@endsection

@push('app-style')
@endpush

@push('app-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script>
    function addCart(id) {
        const qty = document.getElementById('qty-'+id).value;
        // const id = id;

        const url = '/sandbox/api/carts';
        const params = {
            qty: qty,
            product_id: id
        };
        axios.post(url, params)
            .then(function(response){
                let cartitems = response.data;
                document.getElementById('cart-item-count').innerHTML = Object.keys(cartitems).length;

            });
    }
</script>
@endpush
