<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sandbox;

use App\Http\Controllers\Controller;
use App\Models\Product;

/**
 * 商品リスト画面
 */
class ProductsController extends Controller
{
    public function __construct()
    {
    }

    /**
     * 初期表示
     */
    public function index()
    {
        
        \OrderContainer::setOrder('2222');

        $result = Product::get();
        $count = \Cart::count();

        return view('sandbox.product-list', ['products'=>$result, 'cartItemCount'=>$count]);
    }
}
