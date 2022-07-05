<?php
namespace App\Services;

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\Sdk as AwsSdk;
use App\Enums\BcrewCustomerRankTypeDefine;
use App\Exceptions\ApplicationException;
use App\Models\CustomerRank;
use ErrorException;
use Lang;

/**
 * B-crewのDynamoDB関連のリクエストを受付、実行結果を返す。
 *
 * @category  外部連携
 * @package   App\Services
 * @version   1.0
 */
class BcrewService
{
    private $dynamodb;
    private $marshaler;

    /**
     * コンストラクタ
     *
     * @access public
     */
    public function __construct()
    {
        $params = [
            'region'   => config('app.bcrew_dynamodb_region'),
            'version'  => config('app.bcrew_dynamodb_version'),
        ];
        if (config('app.bcrew_dynamodb_endpoint')) {
            $params['endpoint'] = config('app.bcrew_dynamodb_endpoint');
        }
        if (config('app.bcrew_dynamodb_key')) {
            $params['credentials'] = [
                'key'    => config('app.bcrew_dynamodb_key'),
                'secret' => config('app.bcrew_dynamodb_secret'),
            ];
        }

        $sdk = new AwsSdk($params);

        $this->dynamodb = $sdk->createDynamoDb();
        $this->marshaler = new Marshaler();
    }

    /**
     * 会員区分を取得する
     *
     * @access public
     * @param string $bcrewCustomerId b-crewカスタマーID
     * @return array 取得結果 [app_member_type]
     * @throws ApplicationException
     */
    public function getAppMemberType($bcrewCustomerId)
    {
        try {
            $param = [
                "id" => $bcrewCustomerId
            ];
            $key = $this->marshaler->marshalItem($param);

            $tablename = 'customers_dev';
            $params = [
                'TableName' => $tablename,
                'Key' => $key
            ];

            $result = $this->dynamodb->getItem($params);

            $appMemberType = null;
            if (!$result->hasKey('Item')) {
                // データなし
            } else {
                // データあり
                $item = $result->get('Item');
                $recored = $this->marshaler->unmarshalItem($item);

                // Bcrewのアプリ会員区分をレジ側のアプリ会員区分に変換
                $apiMemberType = BcrewCustomerRankTypeDefine::getValue(strtoupper($recored['rank']));

                // ECサイトのランクIDに変換
                $rank = CustomerRank::where('app_member_type', $apiMemberType)->first();
                $appMemberType = $rank->id;
            }

            return $appMemberType;

        } catch (DynamoDbException $e) {
            throw new ApplicationException(Lang::get('messages.E.dynamodb.connection.fail'));
        } catch (ErrorException $e) {
            throw new ApplicationException(Lang::get('messages.E.targetdata.notfound'));
        }
    }
}