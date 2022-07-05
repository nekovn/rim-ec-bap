<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 検索条件保持する場合の戻る
     *
     * @access public
     * @param $values array :key:field名　
     */
    protected function backRedirect(Request $request, $sessionKey = null ,$route, $values=[])
    {
        if ($request->session()->get($sessionKey)) {
            $request->session()->flashInput($sessionKey ? $request->session()->get($sessionKey):$values);
        }
        // 301で返すのはURLの正規化目的、このページは候補に表示する必要無し
        // キャッシュは保存しないようにする(GETでURLが同じだとPHP処理が走らない為)
        return redirect(route($route), Response::HTTP_MOVED_PERMANENTLY)
                ->withHeaders([
                    'Cache-Control' => 'no-store',
                ]);
    }
}
