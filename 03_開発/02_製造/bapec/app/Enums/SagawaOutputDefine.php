<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 佐川連携ファイル出力定義
 *
 * @access public
 * @packege Enums
 */
final class SagawaOutputDefine extends Enum
{
    /** 出荷ギフトフラグ */
    // 1：ギフト
    const SHIPS_GIFT_FLG = '1';

    /** 出力文字コード */
    const ENCODE = 'SJIS';

    /** 出力値 */
    // 顧客ID
    const OUTPUT_VALUE_CUSTOMER_ID = 'BAP';
    // 店舗ID
    const OUTPUT_VALUE_STORE_ID = '';
    // 店舗名
    const OUTPUT_VALUE_STORE_NAME = '';
    // 強制別梱包分割区分（0: 強制個口分割しない）
    const OUTPUT_VALUE_PACKING_SPLIT_CATEGORY = '0';
    // 納品書金額表示フラグ（0: 印字しない）
    const OUTPUT_VALUE_DELIVERY_MONEY_PRINT_FLG = '0';
    // ギフトフラグ（1: ギフト）
    const OUTPUT_VALUE_GIFT_FLG = '1';
    // 予備８ 縦梱包表示フラグ（1: 要）
    const OUTPUT_VERTICAL_PACKING_FLG = '★';

    /** 出力制限 */
    // length -> 桁数制限, conver_flg -> 機種依存文字変換フラグ(true: 変換する)
    // 商品マスタ
    const OUTPUT_GOODS_LIMIT = [
        // 商品コード
        'goods_code' => ['cutting_start_posi' => 0, 'length' =>  30 , 'conver_flg' => false],
        // 商品名
        'goods_name' => ['cutting_start_posi' => 0, 'length' => 160 , 'conver_flg' => true],
        // バーコード
        'goods_barcode' => ['cutting_start_posi' => 0, 'length' => 50 , 'conver_flg' => false],
        // サイズ名
        'goods_size_name' => ['cutting_start_posi' => 0, 'length' => 40 , 'conver_flg' => false],
    ];

    // 出荷指示
    const OUTPUT_SHIPS_LIMIT = [
        // モール受注番号
        'mall_accept_number' => ['cutting_start_posi' => 0, 'length' => 32, 'conver_flg' => false],
        // 伝票番号
        'slip_number' => ['cutting_start_posi' => 0, 'length' => 20, 'conver_flg' => false],
        // 運送会社
        'transport_cd' => ['cutting_start_posi' => 0, 'length' => 4, 'conver_flg' => false],
        // 納品書備考
        'delivery_list_remarks' => ['cutting_start_posi' => 0, 'length' => 250, 'conver_flg' => true],
        // 指定納品日
        'delivery_order_date' => ['cutting_start_posi' => 0, 'length' => 10, 'conver_flg' => false],
        // 指定納品時間
        'delivery_order_time' => ['cutting_start_posi' => 0, 'length' => 2, 'conver_flg' => false],
        // 商品金額計
        'item_detail_total_money' => ['cutting_start_posi' => 0, 'length' => 13, 'conver_flg' => false],
        // 消費税計
        'tax' => ['cutting_start_posi' => 0, 'length' => 13, 'conver_flg' => false],
        // 送料
        'carriage' => ['cutting_start_posi' => 0, 'length' => 13, 'conver_flg' => false],
        // 手数料
        'collect_on_delivery_fee' => ['cutting_start_posi' => 0, 'length' => 13, 'conver_flg' => false],
        // 利用ポイント
        'use_point' => ['cutting_start_posi' => 0, 'length' => 13, 'conver_flg' => false],
        // 割引
        'discount' => ['cutting_start_posi' => 0, 'length' => 13, 'conver_flg' => false],
        // 請求金額計
        'total_money' => ['cutting_start_posi' => 0, 'length' => 13, 'conver_flg' => false],
        // 購入者名１(姓)
        'purchaser_name_1' => ['cutting_start_posi' => 0, 'length' => 20, 'conver_flg' => true],
        // 購入者名２(名)
        'purchaser_name_2' => ['cutting_start_posi' => 0, 'length' => 20, 'conver_flg' => true],
        // 購入者郵便番号１
        'purchaser_post_1' => ['cutting_start_posi' => 0, 'length' => 3, 'conver_flg' => false],
        // 購入者郵便番号２
        'purchaser_post_2' => ['cutting_start_posi' => 0, 'length' => 4, 'conver_flg' => false],
        // 購入者住所１（都道府県）
        'purchaser_address_1' => ['cutting_start_posi' => 0, 'length' => 5, 'conver_flg' => false],
        // 購入者住所２
        'purchaser_address_2' => ['cutting_start_posi' => 0, 'length' => 20, 'conver_flg' => true],
        // 購入者住所３
        'purchaser_address_3' => ['cutting_start_posi' => 20, 'length' => 20, 'conver_flg' => true],
        // 購入者住所４
        'purchaser_address_4' => ['cutting_start_posi' => 40, 'length' => 20, 'conver_flg' => true],
        // 購入者電話番号
        'purchaser_tel' => ['cutting_start_posi' => 0, 'length' => 16, 'conver_flg' => false],
        // 配送先氏名１(姓)
        'ship_name_1' => ['cutting_start_posi' => 0, 'length' => 20, 'conver_flg' => true],
        // 配送先氏名２(名)
        'ship_name_2' => ['cutting_start_posi' => 0, 'length' => 20, 'conver_flg' => true],
        // 配送先郵便番号１
        'ship_post_1' => ['cutting_start_posi' => 0, 'length' => 3, 'conver_flg' => false],
        // 配送先郵便番号２
        'ship_post_2' => ['cutting_start_posi' => 0, 'length' => 4, 'conver_flg' => false],
        // 配送先住所１（都道府県）
        'ship_address_1' => ['cutting_start_posi' => 0, 'length' => 5, 'conver_flg' => false],
        // 配送先住所２
        'ship_address_2' => ['cutting_start_posi' => 0, 'length' => 20, 'conver_flg' => true],
        // 配送先住所３
        'ship_address_3' => ['cutting_start_posi' => 20, 'length' => 20, 'conver_flg' => true],
        // 配送先住所４
        'ship_address_4' => ['cutting_start_posi' => 40, 'length' => 20, 'conver_flg' => true],
        // 配送先電話番号
        'ship_tel' => ['cutting_start_posi' => 0, 'length' => 16, 'conver_flg' => false],
        // 行番号
        'line_number' => ['cutting_start_posi' => 0, 'length' => 3, 'conver_flg' => false],
        // 商品コード
        'product_code' => ['cutting_start_posi' => 0, 'length' => 30, 'conver_flg' => false],
        // 商品名
        'product_name' => ['cutting_start_posi' => 0, 'length' => 80, 'conver_flg' => true],
        // 単価
        'unit_price' => ['cutting_start_posi' => 0, 'length' => 13, 'conver_flg' => false],
        // 数量
        'quantity' => ['cutting_start_posi' => 0, 'length' => 13, 'conver_flg' => false],
        // 明細合計金額
        'item_total_amount' => ['cutting_start_posi' => 0, 'length' => 13, 'conver_flg' => false],
    ];

    /** 出力内容 */
    // 商品マスタ出力
    const OUTPUT_GOODS = 'goods';
    // 出荷指示出力
    const OUTPUT_SHIPS = 'ships';

    /** 出力ファイル名 */
    // 商品マスタ出力
    const OUTPUT_GOODS_FILENAME = '商品マスタ_@timestamp@.csv';
    // 出荷指示出力
    const OUTPUT_SHIPS_FILENAME = '出荷指示_@timestamp@.csv';

    /** ヘッダ出力フラグ（ false: 出力しない true: 出力する ） */
    // 商品マスタ出力
    const OUTPUT_GOODS_HEADER_FLAG = false;
    // 出荷指示出力
    const OUTPUT_SHIPS_HEADER_FLAG = false;

    /** ヘッダ情報 */
    // 商品マスタ出力
    const OUTPUT_GOODS_HEADER = [
        '顧客ID', '商品コード', '商品名', '商品略称名', 'バーコード', '商品カテゴリ',
        'カラー名', 'サイズ名', '売価', '原価', '仕入先コード', '仕入先商品コード',
        '入り数', '才数', '重量', '幅', '奥行', '高さ', '強制別梱包分割区分', '強制別梱包箱数',
        'ロット管理フラグ', 'シリアル管理フラグ', '予備項目３', '予備項目４', '予備項目５'
    ];
    // 出荷指示出力
    const OUTPUT_SHIPS_HEADER = [
        '顧客ID', '店舗ＩＤ', '店舗名', 'モール受注番号', '伝票番号', '運送会社', '納品書備考',
        '決済方法', '出荷予定日', '指定納品日', '指定納品時間', '記事欄１', '記事欄２', '記事欄３',
        '納品書金額表示フラグ', '加工区分１', '加工種類１', '加工メッセージ１',
        '加工区分２', '加工種類２', '加工メッセージ２', '加工区分３', '加工種類３', '加工メッセージ３',
        '加工区分４', '加工種類４', '加工メッセージ４', '加工区分５', '加工種類５', '加工メッセージ５',
        '商品金額計', '消費税計', '送料', '手数料', '利用ポイント', '割引', '請求金額計',
        '購入者名１(姓)', '購入者名２(名)', '購入者郵便番号１', '購入者郵便番号２',
        '購入者住所１（都道府県）', '購入者住所２', '購入者住所３', '購入者住所４',
        '購入者電話番号', '配送先氏名１(姓)', '配送先氏名２(名)', '配送先郵便番号１', '配送先郵便番号２',
        '配送先住所１（都道府県）', '配送先住所２', '配送先住所３', '配送先住所４', '配送先電話番号',
        '配送先日中連絡先', '行番号', '商品コード', '商品名', '単価', '数量', '明細合計金額',
        'ギフト区分', '出荷先コード１', '予備３', '予備４', '予備５', '予備６', '保管場所コード',
        '予備８', '予備９', '予備１０', '予備１１', '予備１２'
    ];
}
