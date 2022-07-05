<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;

/**
 * ダッシュボード画面表示
 */
class DashBoardController extends Controller
{
    /**
     * ダッシュボード画面表示
     * @return ダッシュボード画面
     */
    public function index()
    {
        return view('admin.dashboard');
    }
}
