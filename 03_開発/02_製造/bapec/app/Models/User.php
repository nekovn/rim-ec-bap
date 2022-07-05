<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use BaseTrait;
    use AuthenticationModelTrait;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 利用メニューリストを設定する。Guard用
     *
     * @param array $menus 利用者区分別メニューリスト
     */
    // private function setMenuAuths() {
    //     if ($this->menuAuths!=null) {
    //         return $this->menuAuths;
    //     }
    //     $query = DB::table('m_menu as t1')
    //     ->select([
    //         't1.menu_id', 't2.is_update', 't2.is_reportoutput', 't1.href'
    //     ]);
    //     // ->selectRaw('case when t2.program_id is null then 0 else 1 end as is_read');

    //     $query->join('m_userauth as t2', function ($join) {
    //         $join->on('t1.menu_id', '=', 't2.program_id')
    //         ->where('t2.user_id', '=', $this->id);
    //     });
    //     // $query->where('t1.code', CodeDefine::PROGRAM_ID);
    //     $this->menuAuths = $query->get();
    //  }

    // protected $attributes = [
    //     'menuAuths' => '',
    // ];
}
