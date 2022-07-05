<?php

namespace App\Http\Controllers\Sandbox\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*LaravelShoppingCartでは以下順番でadd()メソッドを実行するとデータをセッションに保持することがでる
        Cart::add(['id' => '293ad', 'name' => 'Product 1', 'qty' => 1, 'price' => 9.99, 'weight' => 550, 'options' => ['size' => 'large']]);
        ↑アレンジはできそう
        ID（ユニークなID）
        商品名
        個数
        金額
        おもさ
        その他パラメータ（配列）
        */
        $product = Product::find($request->product_id);
        // \Cart::add(
        //     $product->id,
        //     $product->name,
        //     $request->qty,//0だと落ちる
        //     $product->unit_price,
        //     1,
        //     ['tax_type' => $product->tax_type]
        // );
        \Cart::add($product, $request->qty, []);

        return \Cart::content();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return \Cart::remove($request->row_id);
    }
}
