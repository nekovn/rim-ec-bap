<?php

namespace App\Enums;

/**
 * グローバル定数
 */
final class Constants
{
    //////////////////////////
    // セッションキー
    //////////////////////////
    /** ゲートウェイ経由のB-crews顧客ID */
    const SES_GW_BCREWS_ID = "gw.bcrews_customer_id";
    /** ゲートウェイ経由のB-crew顧客ID */
    const SES_GW_BCREW_ID = "gw.bcrew_customer_id";
    /** ゲートウェイ経由のB-crew顧客のランクID */
    const SES_GW_RANK_ID = "gw.bcrew_rank_id";
   
    //////////////////////////
    // 商品一覧
    //////////////////////////
    /** 商品一覧画面 １ページあたりの表示件数 */
    const GOODS_LIST_DISP_NUM = 20;
}
