<?php

declare(strict_types=1);

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cart\Facades\Cart;
use Auth;
use OrderContainer;
use App\Models\Goods;

/**
 * カート画面
 */
class CartController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
    }

    /**
     * 初期表示
     */
    public function index()
    {
        // //テスト用
        // foreach (Cart::content() as $item) {
        //     Cart::remove($item->rowId);
        // }
        // $this->test(3, 1);
        // $this->test(2, 2);
        // $this->test(1, 2);
        //------------------------------
        return view('member.cart', ['cartitems' => $this->getReturnCart()]);
    }

    /**
     * セッションCartに追加
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $good = Goods::find($request->goods_id);

        Cart::add($good, $request->qty, []);

        return [];
    }
    /**
     * セッションCartItem数量変更
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Cart::update($request->row_id, $request->qty);

        return $this->getReturnCart();
    }
    /**
     * セッションCartから削除する
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        Cart::remove($request->row_id);

        return $this->getReturnCart();
    }
    /**
     * 返却データ作成
     */
    private function getReturnCart() {
        $cartitems = [
            'items' => Cart::content(), // カートの中身
            'subtotal' => Cart::subtotal(), // 全体の小計
            'tax' => Cart::tax(), // 全体の税
            'total' => Cart::total() // 全合計
        ];
        return $cartitems;
    }
    /**
     * カート内商品点数取得
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function info(Request $request)
    {
        $count = Cart::countItems();

        return ['count' => $count];
    }

    /**
     * 注文入力画面へ
     */
    public function next() {
        // 注文コンテナ生成
        if (Auth::check()) {
            OrderContainer::instance(Auth::user());
        } else {
            OrderContainer::instance();
        }
        // カートを注文明細に変換
        OrderContainer::cartToOrder();
        // 料金計算
        OrderContainer::calculate();

        return redirect('order/input');
    }

    private function test($goodsId, $qty) {
        $good = Goods::find($goodsId);

        Cart::add($good, $qty);

        return Cart::content();
    }
}
