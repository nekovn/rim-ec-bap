<?php
namespace App\Services\Admin;

use App\Enums\CodeDefine;
use App\Enums\StatusDefine;
use App\Helpers\Util\SystemHelper;
use App\Repositories\CategoriesRepository;
use App\Repositories\GoodsRepository;
use App\Repositories\MakersRepository;
use App\Repositories\SuppliersRepository;
use App\Services\SimpleCrudServiceTrait;
use App\Models\CodeValue;
use App\Models\Maker;
use App\Aspect\Annotation\Transactional;

/**
 * 商品管理関連の処理をまとめたサービスクラス
 *
 * @category  商品管理
 * @package   App\Services
 * @version   1.0
 */
class GoodsService
{
    use SimpleCrudServiceTrait;

    /**
     * @var GoodsRepository
     */
    private $repository;

    /**
     * @var CategoriesRepository
     */
    private $categoriesRepository;

    /**
     * @var MakersRepository
     */
    private $makersRepository;

    /**
     * @var SuppliersRepository
     */
    private $suppliersRepository;

    /**
     * コンストラクタ
     *
     * @access public
     * @param GoodsRepository $goodsRepository 商品リポジトリ
     * @param CategoriesRepository $categoriesRepository カテゴリリポジトリ
     * @param MakersRepository $makersRepository メーカーリポジトリ
     * @param SuppliersRepository $suppliersRepository 取引先リポジトリ
     */
    public function __construct(GoodsRepository $goodsRepository,
                                CategoriesRepository $categoriesRepository,
                                MakersRepository $makersRepository,
                                SuppliersRepository $suppliersRepository)
    {
        $this->repository = $goodsRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->makersRepository = $makersRepository;
        $this->suppliersRepository = $suppliersRepository;
    }

    /**
     * 画面の選択肢を取得する。
     *
     * @return array
     */
    public function getScreenSelections(): array
    {
        // カテゴリ
        $categories = $this->categoriesRepository->getCategoryList();

        // メーカー
        $makers = $this->makersRepository->getMakerList();

        // 販売ステータス
        $saleStatuses = SystemHelper::getCodes(CodeDefine::SALE_STATUS);

        // 消費税種類
        $taxKinds = SystemHelper::getCodes(CodeDefine::TAX_KIND);

        // 消費税区分
        $taxTypes = SystemHelper::getCodes(CodeDefine::TAX_TYPE);

        // 取引先
        $suppliers = $this->suppliersRepository->getSupplierList();

        // 在庫管理区分 (プルダウン用配列)
        $stockManagementTypesByPullDown = SystemHelper::getCodes(CodeDefine::STOCK_MANAGEMENT_TYPE);

        // 在庫管理区分 (ラジオボタン用配列)
        $stockManagementTypes = [];
        foreach ($stockManagementTypesByPullDown as $key => $value) {
            array_push($stockManagementTypes, ['value'=>$key, 'label'=>$value]);
        }

        // 納期目安
        $estimatedDeliveryDates = CodeValue::where('code',CodeDefine::ESTIMATED_DELIVERY_DATE)->get();
        $estimatedDeliveryDates = json_decode(json_encode($estimatedDeliveryDates), true);
        $estimatedDeliveryDates = array_column($estimatedDeliveryDates, 'value', 'key');

        // 温度管理区分 (プルダウン用配列)
        $temperatureControlTypesByPullDown = SystemHelper::getCodes(CodeDefine::TEMPERATURE_CONTROL_TYPE);

        // 温度管理区分 (ラジオボタン用配列)
        $temperatureControlTypes = [];
        foreach ($temperatureControlTypesByPullDown as $key => $value) {
            array_push($temperatureControlTypes, ['value'=>$key, 'label'=>$value]);
        }

        return [
            'class1' => $categories['class1'],
            'class2' => $categories['class2'],
            'makers' => $makers,
            'saleStatuses' => $saleStatuses,
            'taxKinds' => $taxKinds,
            'taxTypes' => $taxTypes,
            'suppliers' => $suppliers,
            'stockManagementTypes' => $stockManagementTypes,
            'estimatedDeliveryDates' => $estimatedDeliveryDates,
            'temperatureControlTypes' => $temperatureControlTypes,
        ];
    }

    /**
     * 商品コードの存在チェック
     *
     * @param $checkCode 存在チェックする商品コード
     * @param $excludeCode 検索で除外する商品コード
     * @return $checkCode と同じ商品コードの数
     */
    public function checkDuplicateCode($checkCode, $excludeCode)
    {
        return $this->repository->checkDuplicateCode($checkCode, $excludeCode);
    }

    /**
     * データを１件取得する。
     *
     * @access public
     * @param number $id 主キー
     * @return array
     */
    public function getData($id)
    {
        $where = [
            'id' => $id
        ];
        $rowData = $this->repository->findByPkey($where, false, ['*']);

        // 一覧表示のために取得する
        // メーカー名取得
        $maker = Maker::Target($rowData['maker_id']);
        $rowData['maker_name'] = isset($maker->name) ? $maker->name : '';

        // 公開状況取得
        $rowData['published'] = $rowData['is_published'] == StatusDefine::KOKAI_ON ? '公開' : '非公開';

        // 販売ステータス取得
        $rowData['sale_status_nm'] = $rowData['sale_status']
                                      ? SystemHelper::getCodeValue(CodeDefine::SALE_STATUS, $rowData['sale_status'])
                                      : '';

        return [
            'data' => $rowData,
        ];
    }

    /**
     * データを登録する。
     *
     * @access public
     * @param array $params パラメーター
     * @return Model
     * @Transactional()
     */
    public function store(array $params)
    {
        // 商品マスタ登録
        $model =  $this->repository->create($params);

        $data =  $this->getData($model->id);
        return $data['data'];
    }

    /**
     * データを更新する。
     *
     * @access public
     * @param number $id 主キー
     * @param array $params パラメーター
     * @return Model
     * @Transactional()
     */
    public function update($id, array $params)
    {
        $where = ['id' => $id];
        $where['updated_at'] = $params['ol_updated_at'];

        // 商品マスタ更新
        $model = $this->repository->update($params, $where, true);

        $data =  $this->getData($id);
        return $data['data'];
    }
}
