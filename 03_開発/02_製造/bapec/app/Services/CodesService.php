<?php
namespace App\Services;

use App\Repositories\CodesRepository;
use App\Repositories\CodeValuesRepository;
use App\Services\SimpleCrudServiceTrait;

/**
 * コード管理関連の処理をまとめたサービスクラス
 *
 * @category  コード管理
 * @package   App\Services
 * @version   1.0
 */
class CodesService
{
    use SimpleCrudServiceTrait;

    /**
     * コンストラクタ
     *
     * @access public
     * @param CodeValuesRepository $codeValuesRepository コード値リポジトリ
     * @param CodeRepository $codeRepository コードリポジトリ
     */
    public function __construct(
		CodeValuesRepository $codeValuesRepository,
		CodesRepository $codeRepository
    ) {
			$this->repository = $codeValuesRepository;
			$this->codeRepository = $codeRepository;
	}
    /**
     * 画面のコード選択肢を取得する。
     *
     * @return Collection
     */
    public function getScreenSelections()
    {
        return $this->codeRepository->getScreenSelections();
    }

    /**
     * コード選択肢を取得する。
     *
     * @param string $code コード番号
     * @return array 該当のコードリスト
     */
    public function getCodeSelections($code): array
    {
        $selections = $this->repository->getListData([$code]);
        return $selections[$code];
    }
}
