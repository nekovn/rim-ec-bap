<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use BaseTrait;

    public static function bootDeleteFlagTrait()
    {
        // 削除フラグ無効
    }
    
    /**
     * メニューIDごとに権限フラグを返す
     */
    public static function getAuthFlgs($userId)
    {
        $menus = Menu::select([
                           'menus.program_cd',
                           'user_auths.has_update',
                           'user_auths.has_report_output',
                           'menus.href'
                       ])
                       ->join('user_auths', function ($join) use ($userId) {
                           $join->on('user_auths.program_cd', 'menus.program_cd')
                                ->where('user_auths.user_id', '=', $userId)
                                ;
                       })
                       ->get();
        $flgs = [];
        foreach ($menus as $menu) {
            $flgs[$menu->program_cd] =
                ['has_update'=>$menu->has_update, 'has_report_output'=>$menu->has_report_output];
        }
        return $flgs;
    }
}
