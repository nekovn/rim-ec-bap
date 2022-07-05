<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 商品サムネイルModel
 */
class GoodsImage extends Model
{
    // テーブル名
    protected $table = 'goods_images';

    protected $guarded = ['created_at', 'updated_at'];

    protected $appends = ['image_url'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::saving(function($goodsImage) {
            $url = \Storage::disk(config('app.goods_image_filesystem_driver'))->url('');
            $goodsImage->image = str_replace($url, '', $goodsImage->image);
        });
    }

    public function goods()
    {
        return $this->belongsTo(\App\Models\Goods::class, 'goods_id', 'id');
    }

    /**
     * 画像ファイルURL
     */
    public function getImageUrlAttribute()
    {
        return \Storage::disk(config('app.goods_image_filesystem_driver'))->url($this->image);
    }
}
