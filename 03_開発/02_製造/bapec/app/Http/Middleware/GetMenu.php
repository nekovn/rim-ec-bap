<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Util\SystemHelper;
use App\Http\Menus\GetSidebarMenu;
use Spatie\Permission\Models\Role;

class GetMenu
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
        if (!Auth::check()) {
            view()->share('appMenus', []);
            return $next($request);
        }
        //取得すべきメニューIDを取得
        $userAuths = Auth::user()->menuAuths;

        $programCds = [];

        if (SystemHelper::getAppSettingValue('use-auth-control')) {
            $aryKeys = array_keys($userAuths);
            foreach ($aryKeys as $key) {
                $programCds[] = $key;
            }
        }

        //メニューを取得する
        $menus = new GetSidebarMenu();
        $result = $menus->get($programCds);

        view()->share('appMenus', $result);

        return $next($request);
    }
}
