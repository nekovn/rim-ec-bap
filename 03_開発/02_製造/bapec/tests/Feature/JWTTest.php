<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Firebase\JWT\JWT;
use Illuminate\Http\Response;

class JWTTest extends TestCase
{
    /**
     * JWT認証をモックするためにリクエストヘッダを捏造する
     * @see {@link https://stackoverflow.com/questions/30060360/how-to-mock-tymondesigns-jwt-auth-in-laravel-5}
     */
    public function testBasicTest()
    {
        $payload = array(
            'iss' => 'http://localhost:8000',
            'exp'=>time()+3600,
            'b_crew_customer_id'=>123
        );
        $jwt = JWT::encode($payload, '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890', 'HS256');
        // header('Content-Type: application/json');
        // header('Access-Control-Allow-Origin: *'); // CORS
        $headers['Authorization'] = 'Bearer ' .$jwt;
        echo json_encode(array('token' => $jwt)); // token を返却
        
        $response = $this->get('/index', $headers);
        $response->assertStatus(Response::HTTP_OK);
    }

    // public function api_auth_check() //_JWTありでPOSTするとauthenticated_is_trueが返る()
    // {
    //     // ----------------------------------------
    //     // 1. 下準備
    //     //    JWTトークンヘッダーを捏造する
    //     // ----------------------------------------
    //     $headers = $this->headers();

    //     // ----------------------------------------
    //     // 2. アクション
    //     // ----------------------------------------
    //     $response = $this->postJson(
    //         'api/auth/check',
    //         [],
    //         $headers
    //     );

    //     // ----------------------------------------
    //     // 3. 検証
    //     // ----------------------------------------
    //     $response->assertStatus(Response::HTTP_OK);
    //     $response->assertExactJson(['authenticated' => true]);
    // }
}
