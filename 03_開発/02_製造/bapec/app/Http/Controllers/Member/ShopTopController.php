<?php declare(strict_types=1);

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

/**
 * ショップトップ画面
 */
class ShopTopController extends Controller
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
        return view('member.shopTop', []);
    }
}
