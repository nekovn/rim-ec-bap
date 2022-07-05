<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SimpleCrudControllerTrait;
use App\Http\Requests\UsersDeleteRequest;
use App\Http\Requests\UsersEntryRequest;
use App\Http\Requests\UsersUpdateRequest;
use App\Services\Admin\UsersService;

/**
 * ユーザマスタメンテコントローラー
 */
class UsersController extends Controller
{
    use SimpleCrudControllerTrait {
        SimpleCrudControllerTrait::store  as storeTrait;
        SimpleCrudControllerTrait::update as updateTrait;
        SimpleCrudControllerTrait::delete as deleteTrait;
    }
    /**
     * コンストラクタ
     *
     * @access public
     * @param UsersService $usersService ユーザサービス
     */
    public function __construct(UsersService $usersService)
    {
        $this->service = $usersService;
    }

    /**
     * トップページ表示
     * @return 一覧ページのビューファイル
     */
    protected function getIndexViewFile()
    {
        return 'admin.users';
    }
    /**
     * データを登録する。
     *
     * @access public
     * @param UsersEntryRequest $request リクエスト
     * @return json
     */
    public function store(UsersEntryRequest $request)
    {
        return $this->storeTrait($request);
    }
    /**
     * データを更新する。
     *
     * @access public
     * @param UsersEntryRequest $request リクエスト
     * @param number $id 主キー
     * @return json
     */
    public function update(UsersUpdateRequest $request, $id)
    {
        return $this->updateTrait($request, $id);
    }
    /**
     * データを削除する。
     *
     * @access public
     * @param UsersDeleteRequest $request リクエスト
     * @param number $id 主キー
     * @return json
     */
    public function delete(UsersDeleteRequest $request, $id)
    {
        return $this->deleteTrait($request, $id);
    }
    /*
     * 権限一覧データを取得する。
     *
     * @access public
     * @param number $id
     * @return json
     */
    public function editauth($id)
    {
        $result = $this->service->getAuthData($id);
        return response()->json($result);
    }
    /**
     * 権限データを更新する。
     *
     * @access public
     * @param Request $request リクエスト
     * @param $id m_user.id
     * @return json
     */
    public function updateauth(Request $request, $id)
    {
        //ユーザ権限削除

        $result = $this->service->updateauth($id, $request->all());
        return response()->json($result);
    }
}
