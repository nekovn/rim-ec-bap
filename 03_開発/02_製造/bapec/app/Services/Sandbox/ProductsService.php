<?php
namespace App\Services\Sandbox;

use App\Enums\CodeDefine;
use App\Repositories\ProductsRepository;
use App\Services\SimpleCrudServiceTrait;
use App\Repositories\CodeValuesRepository;
use App\Repositories\UsersRepository;
use App\Repositories\CategoriesRepository;
use App\Models\Product;
use App\Models\CodeValue;

/**
 * 商品管理関連の処理をまとめたサービスクラス
 *
 * @package   App\Services
 * @version   1.0
 */
class ProductsService
{
    use SimpleCrudServiceTrait;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ProductsRepository $productsRepository 商品リポジトリ
     * @param CodeValuesRepository $codeValuesRepository コードリポジトリ
     * @param UsersRepository $usersRepository ユーザリポジトリ
     * @param CategoriesRepository $categoriesRepository カテゴリリポジトリ
     */
    public function __construct(
        ProductsRepository $productsRepository,
        CodeValuesRepository $codeValuesRepository,
        UsersRepository $usersRepository,
        CategoriesRepository $categoriesRepository
    ) {
        $this->repository = $productsRepository;
        $this->codeValuesRepository = $codeValuesRepository;
        $this->usersRepository = $usersRepository;
        $this->categoriesRepository = $categoriesRepository;
    }
    /**
     * 画面の選択肢を取得する。
     *
     * @return array
     */
    public function getScreenSelections(): array
    {

        $selection['class1'] = [];
        $selection['class2'] = [];

        return  $selection;
    }

    /**
     * 最近更新された商品情報を取得
     *
     * @return array 該当データのリスト
     */
    public function getEditedProducts() {
        // 表示対象日数
        $rDay = 30;
        // 表示件数
        $rCnt = 20;
        
        // 現在の日付を取得
        $date = date("Y/m/d H:i:s");
        // code：最近更新された商品表示条件
        $recentlyDays = CodeValue::where('code', CodeDefine::SYSTEM)->where('value', '1')->first();

        if ($recentlyDays->attr_1 != '') {
            $rDay = $recentlyDays->attr_1;
        }
        if ($recentlyDays->attr_2 != '') {
            $rCnt = $recentlyDays->attr_2;
        }
        
        return Product::whereRaw('products.edited_at >= DATE_SUB(\'' . $date . '\',INTERVAL ' . $rDay . ' DAY)')
                    ->orderBy('class_div')->orderBy('id')->limit($rCnt)->get();
    }

    /**
     * 商品情報を取得
     *
     * @param String $product_id 商品ID
     * @return array 該当データのリスト
     */
    public function getProductDetail($product_id) {
        return Product::where('id', $product_id)->first();
    }

    /**
     * 商品一覧情報を取得
     *
     * @param Request $request リクエスト
     * @return array 該当データのリスト
     */
    public function getProductList($request) {
        // URLパラメータ取得
        $param = [];
        if (isset($request->k)) {
            $param['keyword'] = explode(' ', str_replace('　', ' ', mb_strtolower($request->k))); 
        }
        if (isset($request->s)) {
            $param['sort'] = mb_strtolower($request->s);
        }
        if (isset($request->c)) {
            $param['category_code'] = mb_strtolower($request->c);
        }
        if (isset($request->c1)) {
            $param['class1_code'] = mb_strtolower($request->c1);
        }
        if (isset($request->c2)) {
            $param['class2_code'] = mb_strtolower($request->c2);
        }

        // 商品リストを取得
        $productList = $this->repository->getProductList($param);
        
        return $productList;
    }
}
