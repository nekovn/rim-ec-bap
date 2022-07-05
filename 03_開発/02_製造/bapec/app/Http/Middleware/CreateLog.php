<?php

namespace App\Http\Middleware;

use App\Models\TLog;
use Closure;
use DB;
use Route;

/**
 * T_Log登録Middleware
 * web.phpでmiddlewareに指定されたパスのみ通る
 */
class CreateLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        DB::connection()->enableQueryLog();
        
        $response = $next($request);

        $this->createlog($request, $response->status());

        return $response;
    }

    /**
     * ログテーブル作成
     */
    private function createlog($request, $status) {
        //リクエスト内で実行されたSQL
        $querys = DB::connection()->getQueryLog();
        $logSql = null;
        for ($i = 0; $i < count($querys); $i++) {
            $query = $querys[$i];
            $sql = $query['query'];
            $logSql .= "[";
            for ($j = 0; $j < count($query['bindings']); $j++) {
                $sql = preg_replace("/\?/", "'" . $query['bindings'][$j] . "'", $sql, 1);
            }
            $logSql .= $sql."]";
        }
        //登録データ
        $user = $request->user();
        $data = [
            'user_id'=> $user ? $user->user_id : null,
            'route' => Route::currentRouteName(),
            'url' => $request -> path(),
            'status' => $status,
            'message' => count($request->toArray()) != 0 ? json_encode($request->toArray()):null,
            'remote_adr' => $request ->ip(),
            'sqlstr' => $logSql,
        ];

        TLog::create($data);
    }
}
