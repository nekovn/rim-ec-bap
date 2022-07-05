<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Member\VerifyNotification;
use App\Notifications\Member\PasswordResetNotification;
use Illuminate\Support\Str;
use App\Enums\PointKindDefine;
use App\Models\CustomerPoint;
use App\Cart\Contracts\InstanceIdentifier;
use App\Services\BcrewsApiService;
use App\Helpers\Util\SystemHelper;
use App\Enums\CodeDefine;

/**
 * 顧客マスタ Model
 */
class Customer extends Authenticatable implements CanResetPassword, InstanceIdentifier
{
    use MustVerifyEmail, Notifiable;
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
        'password', 'remember_token','created_at', 'created_by', 'updated_by', 'is_deleted'
    ];

    /**
     * Cartコンテナを管理する際の一意となるID
     *
     * @return int|string 顧客ID
     */
    public function getInstanceIdentifier($options = null)
    {
        return $this->id;
    }

    /**
     * Cartコンテナのグローバル割引率
     *
     * @return int|string 割引率
     */
    public function getInstanceGlobalDiscount($options = null)
    {
        // 顧客ランクから割引率を取得
        $rate = 0;
        $rank = $this->currentRank();
        if ($rank && $rank->point_rate) {
            $rate = $rank->point_rate;
        }
        return $rate;
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyNotification());
    }

    /**
     * Send the email password reset notification.
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function($customer) {
            $customer->uuid = Str::uuid();
        });

        self::saving(function($customer) {
            $customer->full_name = $customer->surname . ' ' . $customer->name;
            $customer->full_name_kana = $customer->surname_kana . ' ' . $customer->name_kana;

            $pref = SystemHelper::getCodeValue(CodeDefine::PREF_CD, $customer->prefcode);
            $customer->addr = $pref . $customer->addr_1 . $customer->addr_2 . '　' . $customer->addr_3;
        });
    }

    /**
     * B-crews会員（店舗会員）かどうか
     *
     * @return boolean True：B-crews会員
     */
    public function isBcrewsMember()
    {
        return ($this->bcrews_customer_id != null);
    }

    /**
     * B-crew会員（アプリ会員）かどうか
     *
     * @return boolean True：B-crew会員
     */
    public function isBcrewMember()
    {
        return ($this->bcrew_customer_id != null);
    }

    /**
     * 有効保有ポイント数
     *
     * @return float ポイント数
     */
    public function remainingPoints()
    {
        $point = 0;

        if ($this->isBcrewsMember()) {
            // レジ会員

            // APIで取得
            $bcrewsApiService = new BcrewsApiService();
            $data = $bcrewsApiService->getAppMemberInfo($this->bcrew_customer_id);
            $point = isset($data['bcrew_point']) ? $data['bcrew_point'] : 0;

        } else {
            // アプリ会員 or EC会員
            // BAP専用仕様：有効期限単位は「無期限」しかない想定
            $point = CustomerPoint::where('point_kind', PointKindDefine::COMMON)
                                    // ->where('expiration_date', '>=', Carbon::today())
                                    ->where('customer_id', '=', $this->id)
                                    ->where('is_deleted','=','0')
                                    ->sum('point');
            $point = floor($point);
        }

        return $point;
    }

    /**
     * 顧客の最新ポイント数 アプリからのbcrew_point
     */
    public function setBcrewPoint($value) {
        $this->attributes['bcrew_point'] = $value;
    }
    /**
     * 現在の顧客ランク
     *
     * @return Model 顧客ランクモデル
     */
    public function currentRank()
    {
        $rank = $this->customerRank->first();
        if ($rank) {
            return $rank;
        } else {
            return CustomerRank::find(1);
        }
    }

    // relation
    /**
     * 顧客ランク
     */
    public function customerRank()
    {
        return $this->belongsToMany(\App\Models\CustomerRank::class, 'customer_rank_assigns')->limit(1);
    }

    /**
     * 顧客ポイント
     */
    public function customerPoints()
    {
        return $this->hasMany(CustomerPoint::class);
    }

    /**
     * 顧客情報取得
     */
    public function getCustomerInfo($id)
    {
        $results = Customer::select('*')
                        ->from('customers')
                        ->where('id', '=', $id)
                        ->where('is_deleted','=','0')
                        ->find($id);
        return $results;
    }

}
