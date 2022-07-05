<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\CodeValue;

use App\Enums\CodeDefine;

/**
 * フロント
 * 商品一覧、詳細コントローラー
 */
class GoodsController extends Controller
{
    /**
     * 商品一覧画面表示
     * @return view
     */
    public function search(Request $request)
    {
        $goodslist = new Goods();

        // キーワードパラメータ取得
        $k = explode(" ",trim( preg_replace('/\s+/',' ', mb_convert_kana($request->k,'s') ) ) );
        // 商品一覧検索
        $goodslist = $goodslist->getGoodslist($k, $request->c, $request->s);

        // ページネーションリンクの設定
        $pagenateParams = [];
        if (isset($request->k)) $pagenateParams['k'] = $request->k;
        if (isset($request->c)) $pagenateParams['c'] = $request->c;
        if (isset($request->s)) $pagenateParams['s'] = $request->s;

        return view('member.goods',[
            'goodslist' => $goodslist,
            'pagenateParams'  => $pagenateParams,
            'k' => $request->k,
            'c' => $request->c,
            's' => $request->s
        ]);
    }

    /**
     * 商品詳細画面表示
     *
     * @param string $id 商品ID
     * @return view
     */
    public function index($id)
    {
        $goods = Goods::where('id', $id)->publish()->first();
        if (!$goods) {
            abort(404);
        }
        $goods['estimated_delivery_date_name'] = '';
        if (!is_null($goods->estimated_delivery_date)) {
            $goods['estimated_delivery_date_name'] = CodeValue::where('code',CodeDefine::ESTIMATED_DELIVERY_DATE)->where('key',$goods->estimated_delivery_date)->value('value');
        }
        if (!is_null($goods->sale_status)) {
            $codeValue = CodeValue::selectRaw("case when (code_values.attr_1 is null or code_values.attr_1 = '')
                                                 then code_values.value 
                                                 else code_values.attr_1 
                                               end as sale_status_name")
                            ->where('code', CodeDefine::SALE_STATUS)
                            ->where('key', $goods->sale_status)
                            ->first();
            $goods['sale_status_name'] = $codeValue->sale_status_name;
        }
        return view('member.goodsDetail', ['goods' => $goods]);
    }

}
