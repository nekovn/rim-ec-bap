<?php

namespace App\Services\Member\Auth;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;

/**
 * AWSCognitoのJWTからユーザー情報を取得する
 * 
 * アプリ開発さ来より指定されたdecode（今はこちらを実行）
 * https://qiita.com/tekondo/items/cb18a209371f09d2a276
 * 
 * 証明書が必要とかかれているページ
 * https://qiita.com/ggg-mzkr/items/25abba8d490b054fb00f#cognito%E3%81%AE%E8%A8%AD%E5%AE%9A
 */
class JWTVerifierService {
    /**
     * @param string $jwt
     * @return object|null
     */
    public function decode(string $jwt)
    {
        // $tks = explode('.', $jwt);
        // if (count($tks) !== 3) {
        //     return null;
        // }
        // [$headb64, $_, $_] = $tks;

        // $jwks = $this->fetchJWK();
        // try {
        //     $kid = $this->getKid($headb64);
        //     $jwk = $this->getJWK($jwks, $kid);
        //     $alg = $this->getAlg($jwks, $kid);
        //     return JWT::decode($jwt, $jwk, [$alg]);
        // } catch (\RuntimeException $exception) {
        //     return null;
        // }

        $tks = explode('.', $jwt);
        if (count($tks) !== 3) {
            return null;
        }
        $decode = JWT::urlsafeB64Decode($tks[1]);
        $arr = json_decode($decode, true);
        $obj = new \stdClass;
        foreach($arr as $k => $v) {
            $obj->{$k} = $v;
        }
        return $obj;
    }
    private function getKid(string $headb64)
    {
        $headb64 = json_decode(JWT::urlsafeB64Decode($headb64), true);
        if (array_key_exists('kid', $headb64)) {
            return $headb64['kid'];
        }
        throw new \RuntimeException();
    }

    private function getJWK(array $jwks, string $kid)
    {
        $keys = JWK::parseKeySet($jwks);
        if (array_key_exists($kid, $keys)) {
            return $keys[$kid];
        }
        throw new \RuntimeException();
    }

    private function getAlg(array $jwks, string $kid)
    {
        if (!array_key_exists('keys', $jwks)) {
            throw new \RuntimeException();
        }

        foreach ($jwks['keys'] as $key) {
            if ($key['kid'] === $kid && array_key_exists('alg', $key)) {
                return $key['alg'];
            }
        }
        throw new \RuntimeException();
    }

    private function fetchJWK(): array
    {
        $userPoolId = config('app.bcrew_cognito_userpoolid');
        $response = Http::get("https://cognito-idp.ap-northeast-1.amazonaws.com/${userPoolId}/.well-known/jwks.json");
        return json_decode($response->getBody()->getContents(), true) ?: [];
    }
}