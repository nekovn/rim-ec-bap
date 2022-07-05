<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ShopinfoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * ショップ基本情報 メンテコントローラー
 *
 * @category  ショップ基本情報管理
 * @package   App\Http\Controllers\Admin
 * @version   1.0
 */
class ShopinfoController extends Controller
{

    private $service;

    public function __construct(ShopinfoService $shopinfoService)
    {
        $this->service = $shopinfoService;
    }

    public function index()
    {
        $result['selections'] = $this->service->getScreenSelections();
        return view('admin.shopinfo', $result);
    }

    public function find(): JsonResponse
    {
        $result = $this->service->find();
        return response()->json($result);
    }

    public function store(Request $request): JsonResponse
    {
        $all_request = $request->all();
        $result = $this->service->store($all_request);
        return response()->json($result);
    }
}
