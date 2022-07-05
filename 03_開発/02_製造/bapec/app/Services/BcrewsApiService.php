<?php
namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use App\Exceptions\ApplicationException;
use App\Models\CustomerRank;
use ErrorException;
use Lang;

/**
 * B-crewsのEC連携API関連のリクエストを受付、実行結果を返す。
 *
 * @category  外部連携
 * @package   App\Services
 * @version   1.0
 */
class BcrewsApiService
{
    /**
     * コンストラクタ
     *
     * @access public
     */
    public function __construct(){
    }

    /**
     * ポイント・会員区分を取得する
     *
     * @access public
     * @param string $bcrewCustomerId b-crewカスタマーID
     *                                 注意）b-crewカスタマーIDがnullの場合は404 Not Foundが返るため、
     *                                      呼出側でb-crewカスタマーIDがnullでないかのチェックをすること
     * @return array 取得結果 [id, app_member_type, bcrew_point]
     * @throws ApplicationException
     */
    public function getAppMemberInfo($bcrewCustomerId)
    {
        try{
            $url = config('app.bcrews_api_path') . "customers/${bcrewCustomerId}";
            $headers = $this->createHeaders();
            $response = Http::withHeaders($headers)->get($url);

            // クライアントかサーバエラーが発生した場合は例外を投げる
            if ($response->json()['status'] != 200) {
                throw new RequestException($response);
            }

            $data = $response->json()['data'];
            if (!is_null($data)) {
                // ECサイトの会員IDに変換
                $rank = CustomerRank::where('app_member_type', $data['app_member_type'])->first();
                $data['app_member_type'] = $rank->id;
            }

            return $data;

        } catch (RequestException $e) {
            switch($response->json()['status']) {
                case 400:
                    $data = $response->json();
                    $message = Lang::get('messages.E.api.get.fail') . $data['message'];
                    throw new ApplicationException($message, $response->status());
                    break;
                case 404:
                    throw new ApplicationException(Lang::get('messages.E.targetdata.notfound'), $response->status());
                    break;
                default:
                    throw new ApplicationException(Lang::get('messages.E.api.connection.fail'));
                    break;
            }
        } catch (ConnectionException $e) {
            throw new ApplicationException(Lang::get('messages.E.api.connection.fail'));
        } catch (ErrorException $e) {
            throw new ApplicationException(Lang::get('messages.E.targetdata.notfound'));
        }
    }

    /**
     * ポイントの付与・使用を行う
     *
     * @access public
     * @param string $bcrewCustomerId b-crewカスタマーID
     *                                 注意）b-crewカスタマーIDがnullの場合は404 Not Foundが返るため、
     *                                      呼出側でb-crewカスタマーIDがnullでないかのチェックをすること
     * @param string $pointUseType ポイント付与・利用区分
     *                                    1 : ポイント付与
     *                                    2 : ポイント利用
     * @param string $pointHistoryType ポイント理由区分
     *                                   11 : EC 売上登録
     *                                   12 : EC 売上取消
     * @param number $point ポイント数
     * @return array 実行結果 [id, bcrew_point, bcrew_customer_id]
     * @throws ApplicationException
     */
    public function setAdjustPoint($bcrewCustomerId, $pointUseType, $pointHistoryType, $point)
    {
        try{
            $url = config('app.bcrews_api_path') . "customers/${bcrewCustomerId}/adjust-point";
            $headers = $this->createHeaders();
            $response = Http::withHeaders($headers)->post($url, [
                'point_use_type'     => $pointUseType,
                'point_history_type' => $pointHistoryType,
                'point'              => $point
            ]);

            // クライアントかサーバエラーが発生した場合は例外を投げる
            if ($response->json()['status'] != 200) {
                throw new RequestException($response);
            }

            $data = $response->json()['data'];
            return $data;

        } catch (RequestException $e) {
            switch($response->json()['status']) {
                case 400:
                    $data = $response->json();
                    $message = Lang::get('messages.E.api.get.fail') . $data['message'];
                    throw new ApplicationException($message, $response->status());
                    break;
                case 404:
                    throw new ApplicationException(Lang::get('messages.E.targetdata.notfound'), $response->status());
                    break;
                default:
                    throw new ApplicationException(Lang::get('messages.E.api.connection.fail'));
                    break;
            }
        } catch (ConnectionException $e) {
            throw new ApplicationException(Lang::get('messages.E.api.connection.fail'));
        }
    }

    /**
     * 【未使用】
     * サロン、スタッフ情報を取得する
     * 顧客IDを指定した場合は、その顧客の訪問履歴のあるサロン情報を取得する
     * 顧客IDが未指定の場合は、全サロン情報を取得する
     *
     * @access public
     * @param string|null $bcrewsCustomerId b-crews顧客ID
     * @return array 取得結果 shops : [{
     *                          area_id,
     *                          area_name,
     *                          brand_id,
     *                          brand_name,
     *                          salon_id,
     *                          salon_short_name,
     *                          salon_name,
     *                          direction_type,
     *                          salon_staffs :[{staff_id, staff_name}],
     *                          business_type :[value]
     *                       }]
     * @throws ApplicationException
     */
    public function getSalonstaffs($bcrewsCustomerId = null)
    {
        try{
            $url = config('app.bcrews_api_path') . "salonstaffs";
            $headers = $this->createHeaders();

            if(is_null($bcrewsCustomerId)) {
                // b-crews顧客IDがない場合は開店中の全てのサロンを取得
                // ※2022.03.24 仕様変更によりレジ側APIでサロン取得はできないようにしている
                $response = Http::withHeaders($headers)->get($url);
            } else {
                // b-crews顧客IDがある場合はサロン訪問実績を取得
                $response = Http::withHeaders($headers)->get($url, [
                    'customer_id' => $bcrewsCustomerId
                ]);
            }

            // クライアントかサーバエラーが発生した場合は例外を投げる
            if ($response->json()['status'] != 200) {
                throw new RequestException($response);
            }

            $data = $response->json()['data'];
            return $data;

        } catch (RequestException $e) {
            switch($response->json()['status']) {
                case 400:
                    $data = $response->json();
                    $message = Lang::get('messages.E.api.get.fail') . $data['message'];
                    throw new ApplicationException($message, $response->status());
                    break;
                case 404:
                    throw new ApplicationException(Lang::get('messages.E.targetdata.notfound'), $response->status());
                    break;
                default:
                    throw new ApplicationException(Lang::get('messages.E.api.connection.fail'));
                    break;
            }
        } catch (ConnectionException $e) {
            throw new ApplicationException(Lang::get('messages.E.api.connection.fail'));
        }
    }

    /**
     * 顧客主担当スタッフ情報を取得する
     *
     * @access public
     * @param string $bcrewCustomerId b-crewカスタマーID
     *                                 注意）b-crewカスタマーIDがnullの場合は404 Not Foundが返るため、
     *                                      呼出側でb-crewカスタマーIDがnullでないかのチェックをすること
     * @return array 取得結果 [{
     *                          salon_id,
     *                          salon_name,
     *                          staff_id,
     *                          staff_name,
     *                          staff_display_order
     *                       }]
     * @throws ApplicationException
     */
    public function getMainStaff($bcrewCustomerId)
    {
        try{
            $url = config('app.bcrews_api_path') . "customers/${bcrewCustomerId}/main-staff";
            $headers = $this->createHeaders();
            $response = Http::withHeaders($headers)->get($url);

            // クライアントかサーバエラーが発生した場合は例外を投げる
            if ($response->json()['status'] != 200) {
                throw new RequestException($response);
            }

            $data = $response->json()['data'];
            return $data;

        } catch (RequestException $e) {
            switch($response->json()['status']) {
                case 400:
                    $data = $response->json();
                    $message = Lang::get('messages.E.api.get.fail') . $data['message'];
                    throw new ApplicationException($message, $response->status());
                    break;
                case 404:
                    throw new ApplicationException(Lang::get('messages.E.targetdata.notfound'), $response->status());
                    break;
                default:
                    throw new ApplicationException(Lang::get('messages.E.api.connection.fail'));
                    break;
            }
        } catch (ConnectionException $e) {
            throw new ApplicationException(Lang::get('messages.E.api.connection.fail'));
        } catch (ErrorException $e) {
            throw new ApplicationException(Lang::get('messages.E.targetdata.notfound'));
        }
    }

    /**
     * ヘッダーを作成する
     *
     * @access private
     * @return array ヘッダー
     */
    private function createHeaders() {
        $apiSeacret = config('app.bcrews_api_secret');
        $apiSeacret = explode(':',$apiSeacret);

        return [
            $apiSeacret[0]  => $apiSeacret[1],
        ];
    }
}