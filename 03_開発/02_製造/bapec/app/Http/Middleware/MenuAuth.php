<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Util\SystemHelper;
use Illuminate\Auth\Access\AuthorizationException;

class MenuAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $programCd
     * @return mixed
     */
    public function handle($request, Closure $next, $programCd)
    {
        if (!SystemHelper::getAppSettingValue('use-auth-control')) {
            view()->share('updAuth', true);
            return $next($request);
        }

        $programCd = str_replace("admin.", "", $programCd);
        $roles = $request->user()->menuAuths;
        if (!array_key_exists($programCd, $roles)) {
            // return abort(403);
            throw new AuthorizationException("権限がありません。");
        }
        view()->share('updAuth', $roles[$programCd]['has_update']);
        view()->share('reportAuth',  $roles[$programCd]['has_report_output']);
        return $next($request);
    }
}
