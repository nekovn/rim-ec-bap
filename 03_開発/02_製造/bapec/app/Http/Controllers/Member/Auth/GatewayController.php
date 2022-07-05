<?php

namespace App\Http\Controllers\Member\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Services\Member\MembersService;
use App\Services\Member\Auth\JWTVerifierService;
use App\Models\Customer;
use App\Models\CustomerPoint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use App\Services\BcrewsApiService;
use App\Services\BcrewService;
use App\Http\Controllers\Member\Auth\AuthenticatesMembersTrait;

/**
 * b-crewアプリから呼び出される
 */
class GatewayController extends Controller
{
    use AuthenticatesMembersTrait;

    /**
     * 使用するguardを指定する。
     *
     * @return void
     */
    protected function guard()
    {
        return Auth::guard('member');
    }
    /**
     * コンストラクタ
     *
     * @access public
     */
    public function __construct(
        MembersService $memberService, 
        JWTVerifierService $jwtService, 
        BcrewsApiService $bcrewsApiService,
        BcrewService $bcrewService
    ){
        $this->memberService = $memberService;
        $this->jwtService = $jwtService;
        $this->bcrewsApiService = $bcrewsApiService;
        $this->bcrewService = $bcrewService;
    }

    /**
     * アプリからの呼び出し口
     */
    public function index(Request $request) {

        $request->session()->flush();

        //JWTから情報を取得する TODO::tokenに含まれる名前など合わせる必要がある-----
        // Log::info($request->bearerToken());
        // $token = "eyJraWQiOiJaeE9DKzhzS2RIYXVoeDVZVnZRY1FjN0w5bWhjQWp0T2orbjNFU3QxdlNnPSIsImFsZyI6IlJTMjU2In0.eyJzdWIiOiI4OGVkY2I3MS0yZTYyLTRmZDUtODBlOS1iYzFiYTlhYTRkNmEiLCJldmVudF9pZCI6ImUwNDM1ZmNiLWY1OTYtNDZlMi05YThkLTdlZTJjMWZkNDFhOSIsInRva2VuX3VzZSI6ImFjY2VzcyIsInNjb3BlIjoiYXdzLmNvZ25pdG8uc2lnbmluLnVzZXIuYWRtaW4iLCJhdXRoX3RpbWUiOjE2MzUxNzI3NjEsImlzcyI6Imh0dHBzOlwvXC9jb2duaXRvLWlkcC5hcC1ub3J0aGVhc3QtMS5hbWF6b25hd3MuY29tXC9hcC1ub3J0aGVhc3QtMV9qSHFMRmxSbmQiLCJleHAiOjE2MzUxNzYzNjEsImlhdCI6MTYzNTE3Mjc2MSwianRpIjoiYjRiNTg2YjEtNmM0OC00M2U4LTg4YjUtY2EyNjM0YTUzYmFmIiwiY2xpZW50X2lkIjoic2l0a2k3aWswaHJlaXVhaGVsZjhtNnVmYyIsInVzZXJuYW1lIjoiODhlZGNiNzEtMmU2Mi00ZmQ1LTgwZTktYmMxYmE5YWE0ZDZhIn0.dk5hR2iVJ2Q1ANYvQYNJjFgM4v6DEEw2rl1y6YS5xJYZQaG9KqHvAvN8O_AFSbQVtAg9sGw71b-TLMLdVhSF9kwun0QKNJwosxuB81dVYlnaL7yankrlz3PN77KMvSSE_de0wNtmKnTLc7YPc6F4iRvMwy-VmvIEIXAZx3aYYaa-GpnW7z-seeduCN99qC8mPZGN5xaC_oU3ZytC6zn9oAihHM717Aic65BxCRrnPgFueaR-LK4-178XBM9OsiG_PNDvV7mJghxlPKWQfSGsoJhHB_o1nJ7mMKBQRXiELom2HB0a4Ti1UfdYFFJlWUE6eRZPiS-XS6UTCvyzvYySaQ";
        // $token = $request->bearerToken();
        $token = $request->token;
        Log::debug($token);
        // if (!$token) {
        //     return redirect('/app-info');
        // }
        $cid = $request->cid;

        $bcrewCustomerId = '';
        $email = '';

        if ($token) {
            // トークンがある場合
            $decode = $this->jwtService->decode($token);
            // Log::debug($decode);
            if ($decode == null || !$decode->sub) {
                return abort(404);
            }
            // $token = $request->bearerToken();
            // $decode = $this->jwtService->decode($token);
            $bcrewCustomerId = $decode->sub;
            $email = isset($decode->email) ?: '';
        } else if($cid) {
            // bcrewカスタマーIDがある場合
            $bcrewCustomerId = $cid;
        } else {
            // トークンもbcrewカスタマーIDもない場合
            return redirect('/app-info');
        }
        // Log::info($bcrewInfo['b_crew_customer_id']);
        //------------------------------------------------
        
        //test　アプリからログイン時は上記を有効に------
        // $bcrewCustomerId = 1234;
        // $email = 'test@email.com';
        // $bcrewInfo['b_crew_customer_id'] = '1235';//ec23185c-be83-4766-9e36-5922194c6573';
        // $bcrewCustomerId = $bcrewInfo['b_crew_customer_id'];
        //----------------

        Log::debug($bcrewCustomerId);
        // DynamoDBから現在のランクを取得
        $rankId = $this->bcrewService->getAppMemberType($bcrewCustomerId);
        if (!$rankId) {
            return abort(404);
        }

        //APIを呼び出し、b-crews（レジ）側の顧客情報を取得
        $apiUserInfo  = $this->bcrewsApiService->getAppMemberInfo($bcrewCustomerId);

        //アプリ情報から顧客情報取得
        $customer = Customer::where('bcrew_customer_id','=', $bcrewCustomerId)->first();
        if (!$customer) { //アプリ・EC連携なし

            // セッションにアプリ情報を格納
            if ($bcrewCustomerId) {
                $request->session()->put(\Consts::SES_GW_BCREW_ID, $bcrewCustomerId);
            }
            if ($apiUserInfo && $apiUserInfo['id']) {
                $request->session()->put(\Consts::SES_GW_BCREWS_ID, $apiUserInfo['id']);
            }
            if ($rankId) {
                $request->session()->put(\Consts::SES_GW_RANK_ID, $rankId);
            }

            // 新規登録画面へリダイレクト
            return $this->backRedirect($request, null, 'member.register-entry',['email'=>$email]);
        }
        $customerId = $customer->id;

        if (is_null($customer->bcrews_customer_id) && $apiUserInfo && $apiUserInfo['id']) {
            $customerPoint = CustomerPoint::target($customerId);
            if (isset($customerPoint->point)) {
                // 顧客ポイント残高が存在する場合はポイントをレジ側へポイントを移行する
                // レジ側ポイント調整API
                $apiPoint = $this->bcrewsApiService->setAdjustPoint($customer->bcrew_customer_id, 1, 11, (int)$customerPoint->point);
                $apiUserInfo['bcrew_point'] = $apiPoint['bcrew_point'];

                // 顧客ポイント残高を論理削除する
                $customerPoint->delete();
            }
        }

        //ログインを行う b-crewからの顧客コードのみでcustomerを取得し、その情報でログイン
        //下記Updateでupdated_byがAuth::user()->idとなるのでログインを先にする
        $this->guard()->login($customer, false);

        if ($apiUserInfo) {
            // b-crewポイント保持
            Auth::user()->bcrew_point = $apiUserInfo['bcrew_point'];
        }

        if ($rankId) {
            // ランクを更新
            $this->memberService->reflectMemberType($customerId, $rankId);
        }

        // レジ側顧客IDを更新
        $bcrewsCustomerId = ($apiUserInfo && $apiUserInfo['id']) ? $apiUserInfo['id'] : null;
        Customer::where('id', $customerId)
                ->update(['bcrews_customer_id' => $bcrewsCustomerId]);

        return $this->sendLoginResponse($request);
    }

    /**
     * ログイン後の遷移先
     */
    protected function redirectTo()
    {
        return route('member.shopTop');
    }
}
