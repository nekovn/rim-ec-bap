<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SimpleCrudControllerTrait;
use App\Http\Requests\CustomersRegisterRequest;
use App\Http\Requests\CustomersUpdateRequest;
use App\Services\Admin\CustomersService;

/**
 * 顧客マスタメンテコントローラー
 */
class CustomersController extends Controller
{
    use SimpleCrudControllerTrait {
        SimpleCrudControllerTrait::store  as storeTrait;
        SimpleCrudControllerTrait::update as updateTrait;
    }

    /**
     * コンストラクタ
     *
     * @access public
     * @param CustomersService $customersService 顧客サービス
     */
    public function __construct(CustomersService $customersService)
    {
        $this->service = $customersService;
    }

    /**
     * トップページ表示
     * @return string 一覧ページのビューファイル
     */
    protected function getIndexViewFile(): string
    {
        return 'admin.customers';
    }

    /**
     * データを登録する。
     *
     * @access public
     * @param CustomersRegisterRequest $request リクエスト
     * @return json
     */
    public function store(CustomersRegisterRequest $request)
    {
        return $this->storeTrait($request);
    }

    /**
     * データを更新する。
     *
     * @access public
     * @param CustomersUpdateRequest $request リクエスト
     * @param number $id 主キー
     * @return json
     */
    public function update(CustomersUpdateRequest $request, $id)
    {
        return $this->updateTrait($request, $id);
    }
}
