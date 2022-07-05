<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['created_at'];

    protected $hidden = ['created_at'];

    protected $primaryKey = 'code';

   /**
    * リレーション　商品カテゴリ
    */
    public function goodsCategory()
    {
        return $this->hasMany(GoodsCategory::class, 'category_code', 'code');
    }
    /**
    * リレーション　商品
    */
    public function goods()
    {
        return $this->hasManyThrough(Goods::class, GoodsCategory::class, 'category_code', 'id', 'code', 'goods_id');
    }

    /**
     * 自分の一つ上の親を取得する
     *
     * @return Category
     */
    public function getParent()
    {
        $path = rtrim($this->path, '~');
        $paths = explode("~", $path);
        array_pop($paths);
        if (count($paths)) {
            $last = end($paths);
            if ($last != "") {
                $parent = Category::find($last);
                if ($parent) {
                    return $parent;
                }
            }
        }
        return null;
    }
}
