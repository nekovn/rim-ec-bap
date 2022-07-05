@php
$screenName = "";
@endphp
@extends('member.layouts.app')


@section('content')
<div class="container">
    {{Form::open(['route'=>'sandbox.checkout', 'method'=>'POST'])}}
        <h1>カートの中身</h1>
        <table class="table">
            <tr>
                <th>商品</th>
                <th>個数</th>
                <th>単価</th>
                <th>販売価格</th>
                <th>小計</th>
                <th></th>
            </tr>
            @foreach ($cartitems['items'] as $rowId => $ci)
            <tr >
                <td>
                    <span >{{$ci->name}}</span> 
                    {{-- （サイズ： <span v-text="cartItem.options.size"></span>） --}}
                </td>
                <td >{{$ci->qty}}</td>
                <td >{{$ci->unitPrice}}</td>
                <td >{{$ci->salePrice}}</td>
                <th >{{$ci->tax}}</th>
                <th >{{$ci->subtotalTaxIncluded}}</th>
                <td class="text-right">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItem('{{$rowId}}',{{$ci->id}})">削除</button>
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5"></td>
                <th>小計</th>
                <th>{{$cartitems['subtotal']}}</th>
            </tr>
            <tr>
                <td colspan="5"></td>
                <th>税</th>
                <th>{{$cartitems['tax']}}</th>
            </tr>
            <tr>
                <td colspan="5"></td>
                <th>合計</th>
                <th>{{$cartitems['total']}}</th>
            </tr>
        </table>
        <div class="text-right">
            <button type="submit" class="btn btn-success">お会計へ</button>
        </div>
        <a href="{{ route('sandbox.products')}}" class="btn btn-info">戻る</a>
    
    {{Form::close()}}
</div>

@endsection

@push('app-style')
@endpush

@push('app-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script>
    function removeItem(rowId, cartId) {

        if(confirm('商品を削除します。よろしいですか？')) {

            // var cart = this.carts.items[rowId];
            var url = '/sandbox/api/carts/'+ cartId;
            var params = {
                row_id: rowId,
                _method: 'delete'
            };
            axios.post(url, params)
                .then(function(response){

                    // self.getCarts();
                    //再表示ロジック
                    alert('削除完了。再表示plz')

                });

        }
    }
</script>
@endpush
