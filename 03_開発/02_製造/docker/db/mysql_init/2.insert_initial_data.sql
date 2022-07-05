-- Project Name : Bap
-- Date/Time    : 2021/09/10 10:13:04
-- Author       : else if
-- RDBMS Type   : MySQL
-- Application  : A5:SQL Mk-2

/*
  << 注意！！ >>
  BackupToTempTable, RestoreFromTempTable疑似命令が付加されています。
  これにより、drop table, create table 後もデータが残ります。
  この機能は一時的に $$TableName のような一時テーブルを作成します。
  この機能は A5:SQL Mk-2でのみ有効であることに注意してください。
*/

-- 受注
--* BackupToTempTable
drop table if exists `orders` cascade;

--* RestoreFromTempTable
create table `orders` (
  `id` serial not null auto_increment comment 'ID'
  , `ordered_at` datetime not null comment '受注日時'
  , `status` varchar(10) not null comment '受注ステータス:コード（受注ステータス）'
  , `customer_id` bigint unsigned not null comment '顧客ID:非会員の場合「0」'
  , `surname` varchar(50) comment '姓'
  , `name` varchar(50) comment '名'
  , `surname_kana` varchar(100) comment 'セイ'
  , `name_kana` varchar(100) comment 'メイ'
  , `zip` varchar(7) comment '郵便番号'
  , `prefcode` varchar(2) comment '都道府県コード'
  , `addr_1` varchar(100) comment '住所１'
  , `addr_2` varchar(100) comment '住所２'
  , `addr_3` varchar(100) comment '住所３'
  , `addr` varchar(500) comment '住所'
  , `tel` varchar(21) comment '電話番号'
  , `email` varchar(254) comment 'メールアドレス'
  , `payment_method` varchar(10) comment '決済方法'
  , `comment` text comment '注文コメント:注文時に入力された内容'
  , `goods_total_tax` decimal(12,2) unsigned default 0 not null comment '商品合計（消費税）:受注明細　消費税の合計'
  , `goods_total_tax_included` decimal(12,2) unsigned default 0 not null comment '商品合計（税込）:受注明細　小計（税込）の合計'
  , `postage_total` decimal(12,2) unsigned default 0 not null comment '送料合計:受注配送先　送料の合計'
  , `payment_fee_total` decimal(12,2) unsigned default 0 not null comment '決済手数料合計:受注配送先　決済手数料の合計'
  , `packing_charge_total` decimal(12,2) unsigned default 0 not null comment '梱包料合計:受注配送先　梱包料の合計'
  , `other_fee_total` decimal(12,2) unsigned default 0 not null comment 'その他手数料合計:受注配送先　その他手数料の合計'
  , `discount` decimal(12,2) unsigned default 0 not null comment '割引額:管理サイトより手入力された割引額'
  , `promotion_discount_total` decimal(12,2) unsigned default 0 not null comment 'プロモーション値引額合計'
  , `coupon_discount_total` decimal(12,2) unsigned default 0 not null comment 'クーポン値引額合計'
  , `earned_points` decimal(12,2) unsigned default 0 not null comment '獲得ポイント'
  , `used_point` decimal(10,2) unsigned default 0 not null comment '利用ポイント:ポイント数（金額ではない）'
  , `point_amount` decimal(12,2) unsigned default 0 not null comment 'ポイント利用額:ポイントを金額に換算したもの'
  , `point_conversion_rate` decimal(10,2) unsigned default 0 not null comment 'ポイント換算レート:1ポイント何円とするか'
  , `total` decimal(12,2) unsigned default 0 not null comment '合計'
  , `customer_rank_id` bigint unsigned comment '顧客ランクID:注文時点の顧客ランク'
  , `remark` text comment '管理側備考'
  , `bcrews_salon_id` bigint unsigned comment 'B-crewsサロンID:行きつけのサロンスタッフを選択した情報'
  , `bcrews_salon_short_name` varchar(100) comment 'B-crewsサロン店舗名:行きつけのサロンスタッフを選択した情報'
  , `bcrews_staff_id` bigint unsigned comment 'B-crewsサロンスタッフID:行きつけのサロンスタッフを選択した情報'
  , `bcrews_staff_name` varchar(100) comment 'B-crewsサロンスタッフ名:行きつけのサロンスタッフを選択した情報'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `orders_PKC` primary key (`id`)
) comment '受注' ;

-- 商品カテゴリ
--* BackupToTempTable
drop table if exists `goods_categories` cascade;

--* RestoreFromTempTable
create table `goods_categories` (
  `category_code` varchar(100) not null comment 'カテゴリコード'
  , `goods_id` bigint unsigned not null comment '商品ID'
  , constraint `goods_categories_PKC` primary key (`category_code`,`goods_id`)
) comment '商品カテゴリ' ;

-- 決済ログ
--* BackupToTempTable
drop table if exists `payment_logs` cascade;

--* RestoreFromTempTable
create table `payment_logs` (
  `id` serial not null auto_increment comment 'ID'
  , `payment_process_type` varchar(10) not null comment '決済処理区分:コード（決済処理区分）'
  , `customer_id` bigint unsigned comment '顧客ID'
  , `payment_customer_id` varchar(100) comment '決済会員ID:決済用顧客ID'
  , `order_id` bigint unsigned comment '受注ID'
  , `transation_id` varchar(100) comment '取引ID:GMOのオーダーID'
  , `card_edit_no` varchar(100) comment 'カード編集番号:GMOのカード編集番号'
  , `result` varchar(100) comment '処理結果'
  , `process_date` datetime comment '処理日時'
  , `err_code` varchar(3) comment 'エラーコード'
  , `err_info` varchar(9) comment 'エラー詳細'
  , `response` text comment 'レスポンス:JSONレスポンスをそのまま保持。ただし、カード番号は除く'
  , constraint `payment_logs_PKC` primary key (`id`)
) comment '決済ログ' ;

-- 顧客行動
--* BackupToTempTable
drop table if exists `customer_behaviors` cascade;

--* RestoreFromTempTable
create table `customer_behaviors` (
  `customer_id` bigint unsigned not null comment '顧客ID'
  , `useragent` varchar(512) comment 'ユーザーエージェント:最終アクセス時のUserAgent'
  , `last_logined_at` datetime comment '最終ログイン日時'
  , `last_orderd_at` datetime comment '最終購入日時'
  , `purchases_count` int unsigned comment '総購入回数'
  , `purchases_amount` decimal(12,0) unsigned comment '総購入金額:税込み、送料、手数料除く'
  , constraint `customer_behaviors_PKC` primary key (`customer_id`)
) comment '顧客行動' ;

-- 倉庫
--* BackupToTempTable
drop table if exists `warehouses` cascade;

--* RestoreFromTempTable
create table `warehouses` (
  `id` serial not null auto_increment comment 'ID'
  , `name` varchar(200) not null comment '倉庫名'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `warehouses_PKC` primary key (`id`)
) comment '倉庫' ;

-- ポイント運用
--* BackupToTempTable
drop table if exists `points` cascade;

--* RestoreFromTempTable
create table `points` (
  `id` serial not null auto_increment comment 'ID'
  , `kind` varchar(10) not null comment 'ポイント種類:コード（ポイント種類）'
  , `name` varchar(100) not null comment 'ポイント名'
  , `is_valid` tinyint(1) default 1 not null comment '使用フラグ:0：無効　1：有効'
  , `acquisition_unit` decimal(10,0) unsigned not null comment 'ポイント付与単位'
  , `acquisition_rate` decimal(4,1)  comment 'ポイント付与率'
  , `conversion_rate` decimal(10,0) unsigned default 1 not null comment 'ポイント換算レート:1ポイント何円で使用できるか'
  , `rounding_type` varchar(10) comment 'ポイント端数処理区分:コード（端数処理区分）'
  , `expiration_type` varchar(10) comment '有効期限単位:コード（有効期限区分）'
  , `expiration_date` decimal(10,0) unsigned comment '有効期限数'
  , `remark` text comment '備考'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `points_PKC` primary key (`id`)
) comment 'ポイント運用' ;

-- 決済
--* BackupToTempTable
drop table if exists `settlements` cascade;

--* RestoreFromTempTable
create table `settlements` (
  `id` serial not null auto_increment comment 'ID'
  , `code` varchar(10) not null comment 'コード'
  , `name` varchar(100) not null comment '決済名'
  , `display_name` varchar(100) not null comment '表示用名称'
  , `sequence` int(10) unsigned default 100 not null comment '表示順'
  , `payment_fee` decimal(12,2) unsigned default 0 not null comment '決済手数料:税込み'
  , `upper_limit` decimal(12,2) unsigned comment '利用可能上限金額:NULLの場合制限なし'
  , `lower_limit` decimal(12,2) unsigned comment '利用可能下限金額:NULLの場合制限なし'
  , `explanation` text comment '説明'
  , `remark` text comment '備考'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `settlements_PKC` primary key (`id`)
) comment '決済' ;

-- 顧客ポイント残高
--* BackupToTempTable
drop table if exists `customer_points` cascade;

--* RestoreFromTempTable
create table `customer_points` (
  `id` serial not null auto_increment comment 'ID'
  , `customer_id` bigint unsigned not null comment '顧客ID'
  , `point_kind` varchar(10) not null comment 'ポイント種類:コード（ポイント種類）'
  , `expiration_date` date comment '有効期限'
  , `point` decimal(12,2) unsigned default 0 not null comment 'ポイント残高'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `customer_points_PKC` primary key (`id`)
) comment '顧客ポイント残高' ;

-- 在庫
--* BackupToTempTable
drop table if exists `goods_stocks` cascade;

--* RestoreFromTempTable
create table `goods_stocks` (
  `goods_id` bigint unsigned not null comment '商品ID'
  , `warehouse_id` bigint unsigned not null comment '倉庫ID'
  , `quantity` decimal(10,0) default 0 not null comment '良品在庫数'
  , `b_grade_quantity` decimal(10,0) default 0 not null comment '不良品在庫数'
  , `return_quantity` decimal(10,0) default 0 not null comment '顧客返品数'
  , `evacuees_quantity` decimal(10,0) default 0 not null comment '退避数'
  , `discarded_quantity` decimal(10,0) default 0 not null comment '廃棄数'
  , `shipped_quantity` decimal(10,0) default 0 not null comment '出荷済数'
  , `assigned_quantity` decimal(10,0) default 0 not null comment '引当済数'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `goods_stocks_PKC` primary key (`goods_id`,`warehouse_id`)
) comment '在庫' ;

-- インポートログ
--* BackupToTempTable
drop table if exists `import_logs` cascade;

--* RestoreFromTempTable
create table `import_logs` (
  `id` bigint(20) unsigned auto_increment not null comment 'id'
  , `type` varchar(5) not null comment '処理区分:コード（取込処理区分）'
  , `upload_date` datetime not null comment '実行取込日時'
  , `status` varchar(5) not null comment 'ステータス:1：正常終了　2：一部エラー　3：エラー'
  , `file_name` varchar(255) not null comment '取り込みファイル'
  , `log_file_name` varchar(255) comment 'ログファイル'
  , `message` text comment 'メッセージ'
  , constraint `import_logs_PKC` primary key (`id`)
) comment 'インポートログ' ;

-- 取引先
--* BackupToTempTable
drop table if exists `suppliers` cascade;

--* RestoreFromTempTable
create table `suppliers` (
  `id` serial not null auto_increment comment 'ID'
  , `name` varchar(200) not null comment '取引先名'
  , `supplier_kind` varchar(10) comment '取引先種類:コード（取引先種類）'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `suppliers_PKC` primary key (`id`)
) comment '取引先' ;

-- メーカー
--* BackupToTempTable
drop table if exists `makers` cascade;

--* RestoreFromTempTable
create table `makers` (
  `id` serial not null auto_increment comment 'ID'
  , `name` varchar(100) not null comment 'メーカー名'
  , `name_kana` varchar(100) comment 'メーカー名（カナ）'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `makers_PKC` primary key (`id`)
) comment 'メーカー' ;

-- 配送業者都道府県別配送条件
--* BackupToTempTable
drop table if exists `delivery_pref_conditions` cascade;

--* RestoreFromTempTable
create table `delivery_pref_conditions` (
  `id` serial not null auto_increment comment 'ID'
  , `carrier_id` bigint unsigned not null comment '配送業者ID'
  , `prefcode` varchar(2) not null comment '都道府県コード'
  , `delivery_leadtime` numeric(3) default 0 not null comment '配送リードタイム'
  , `postage` decimal(12,2) unsigned default 0 not null comment '送料'
  , `cool_postage` decimal(12,2) unsigned comment 'クール便送料'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `delivery_pref_conditions_PKC` primary key (`id`)
) comment '配送業者都道府県別配送条件' ;

-- 顧客ポイント履歴
--* BackupToTempTable
drop table if exists `customer_point_logs` cascade;

--* RestoreFromTempTable
create table `customer_point_logs` (
  `id` serial not null comment 'ID'
  , `customer_id` bigint unsigned not null comment '顧客ID'
  , `trans_at` datetime default current_timestamp not null comment '移動日時'
  , `point_kind` varchar(10) not null comment 'ポイント種類:コード（ポイント種類）'
  , `transfer_type` varchar(10) not null comment '移動区分:コード（ポイント移動区分）'
  , `transfer_reason` varchar(10) not null comment '移動理由:コード（ポイント移動理由）'
  , `point` decimal(12,2) unsigned default 0 comment 'ポイント'
  , `expiration_date` date comment '有効期限'
  , `transaction_id` bigint unsigned comment 'トランザクションID:移動の理由となったデータのID'
  , `adjust_reason` varchar(1000) comment 'ポイント調整理由'
  , `after_point` decimal(12,2) unsigned comment '移動後保有ポイント'
  , `is_fixed` tinyint(1) default 0 comment '確定フラグ:0：仮　1：確定'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `customer_point_logs_PKC` primary key (`id`)
) comment '顧客ポイント履歴' ;

-- 会員ランクアサイン
--* BackupToTempTable
drop table if exists `customer_rank_assigns` cascade;

--* RestoreFromTempTable
create table `customer_rank_assigns` (
  `customer_id` bigint unsigned not null comment '顧客ID'
  , `customer_rank_id` bigint unsigned not null comment 'ランクID'
  , `next_start_date` date comment '次回適用開始日'
  , `next_customer_rank_id` bigint unsigned comment '次回ランク'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `customer_rank_assigns_PKC` primary key (`customer_id`)
) comment '会員ランクアサイン' ;

-- 受注決済
--* BackupToTempTable
drop table if exists `order_payments` cascade;

--* RestoreFromTempTable
create table `order_payments` (
  `id` serial not null comment 'ID'
  , `transaction_id` varchar(100) comment '取引ID:GMOのorderID'
  , `order_id` bigint unsigned not null comment '受注ID'
  , `payment_method` bigint unsigned not null comment '決済方法'
  , `payment_status` varchar(10) not null comment '決済ステータス:コード（決済ステータス）'
  , `payment_amount` decimal(12,2) unsigned default 0 not null comment '決済金額（税抜）'
  , `payment_tax` decimal(12,2) unsigned default 0 not null comment '決済消費税額'
  , `payment_amount_tax_included` decimal(12,2) unsigned default 0 not null comment '決済金額（税込）'
  , `payment_fee` decimal(12,2) unsigned default 0 not null comment '決済手数料'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `order_payments_PKC` primary key (`id`)
) comment '受注決済' ;

-- ショップ運用
--* BackupToTempTable
drop table if exists `shops` cascade;

--* RestoreFromTempTable
create table `shops` (
  `id` serial not null comment 'ID'
  , `code` varchar(100) not null comment 'ショップコード'
  , `shop_name` varchar(100) not null comment 'ショップ名'
  , `company_name` varchar(100) comment '会社名'
  , `company_name_kana` varchar(100) comment '会社名カナ'
  , `company_zip` varchar(7) comment '郵便番号'
  , `company_pref` varchar(2) comment '都道府県'
  , `company_addr1` varchar(100) comment '住所1:市区町村'
  , `company_addr2` varchar(100) comment '住所2:町域番地'
  , `company_addr3` varchar(100) comment '住所3:建物名など'
  , `company_tel` varchar(21) comment '電話番号'
  , `company_fax` varchar(21) comment 'FAX番号'
  , `representative_name` varchar(100) comment '代表者名'
  , `representative_email` varchar(254) comment '代表メールアドレス'
  , `tax_rounding_type` varchar(10) not null comment '消費税端数処理区分:コード（端数処理区分）'
  , `discount_rounding_type` varchar(10) not null comment '割引端数処理区分:コード（端数処理区分）'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `shops_PKC` primary key (`id`)
) comment 'ショップ運用' ;

-- コード
--* BackupToTempTable
drop table if exists `codes` cascade;

--* RestoreFromTempTable
create table `codes` (
  `id` bigint(20) unsigned auto_increment not null comment 'id'
  , `code` varchar(5) comment 'コード'
  , `code_name` varchar(50) comment '名称'
  , `pg_key` varchar(50) comment 'プログラムキー'
  , `sequence` int(10) unsigned comment '並び順'
  , `se_only` tinyint(1) default 0 comment 'SEメンテ:0：通常、1：SEのみメンテ可能'
  , constraint `codes_PKC` primary key (`id`)
) comment 'コード' ;

-- 顧客
--* BackupToTempTable
drop table if exists `customers` cascade;

--* RestoreFromTempTable
create table `customers` (
  `id` serial not null auto_increment comment 'ID:'
  , `customer_type` varchar(10) default 1 not null comment '顧客区分:コード（1：個人　2：法人）'
  , `surname` varchar(50) comment '姓'
  , `name` varchar(50) comment '名'
  , `surname_kana` varchar(100) comment '姓（フリガナ）'
  , `name_kana` varchar(100) comment '名（フリガナ）'
  , `full_name` varchar(100) comment '姓名'
  , `full_name_kana` varchar(200) comment '姓名（カナ）'
  , `gender` varchar(1) default 9 not null comment '性別:1：女性　2：男性　3：その他　9：無回答'
  , `birthday_year` smallint unsigned comment '生年月日・年'
  , `birthday_month` tinyint unsigned comment '生年月日・月'
  , `birthday_day` tinyint unsigned comment '生年月日・日'
  , `zip` varchar(7) comment '郵便番号'
  , `prefcode` varchar(2) comment '都道府県コード:コード値'
  , `addr_1` varchar(100) comment '住所１:市区町村名'
  , `addr_2` varchar(100) comment '住所２:町域番地'
  , `addr_3` varchar(100) comment '住所３:建物名等'
  , `addr` varchar(500) comment '住所:都道府県名+住所１+住所２＋住所３'
  , `tel` varchar(21) comment '電話番号'
  , `email` varchar(254) not null comment 'メールアドレス'
  , `password` varchar(255) comment 'パスワード'
  , `corporate_name` varchar(200) comment '法人名'
  , `position` varchar(100) comment '肩書'
  , `is_login_prohibited` tinyint(1) default 0 not null comment 'ログイン禁止フラグ:0：通常　1：ログイン禁止'
  , `is_locked` tinyint(1) default 0 not null comment 'アカウントロックフラグ:0：通常　1：ロック'
  , `can_dm_send` tinyint(1) default 1 not null comment 'DM送付可能:0：不可　1：送付可'
  , `can_mailmagazine_send` tinyint(1) default 1 not null comment 'メルマガ送付可能:0：不可　1：送付可'
  , `remark` text comment '管理側メモ'
  , `bcrews_customer_id` bigint unsigned comment 'B-crews顧客ID:B-crews(レジ)側の顧客マスタのID'
  , `bcrew_customer_id` varchar(255) comment 'B-crewカスタマーID:B-crew(アプリ)側の顧客マスタのID'
  , `remember_token` varchar(255) comment 'パスワード忘れトークン'
  , `email_verified_at` datetime comment 'メールアドレス認証日時'
  , `uuid` varchar(36) not null comment 'UUID:公開API用'
  , `payment_member_id` varchar(60) comment '決済会員ID:GMO会員ID'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `customers_PKC` primary key (`id`)
) comment '顧客' auto_increment = 1000000000;

-- 商品
--* BackupToTempTable
drop table if exists `goods` cascade;

--* RestoreFromTempTable
create table `goods` (
  `id` serial not null auto_increment comment 'ID'
  , `code` varchar(20) not null comment '商品コード'
  , `sku_code` varchar(50) not null comment '商品SKUコード'
  , `name` varchar(200) comment '商品名'
  , `volume` varchar(100) comment '規格'
  , `jan_code` varchar(20) comment 'JANコード'
  , `maker_id` bigint unsigned comment 'メーカーID'
  , `unit_price` decimal(12,2) unsigned not null comment '単価'
  , `tax_kind` varchar(10) not null comment '消費税種類:コード（税種類）'
  , `tax_type` varchar(10) not null comment '消費税区分:コード（税区分）'
  , `supplier_id` bigint unsigned comment '仕入先ID'
  , `purchase_unit_price` decimal(12,2) unsigned comment '仕入単価'
  , `purchase_tax_kind` varchar(10) comment '仕入消費税種類:コード（税種類）'
  , `purchase_tax_type` varchar(10) comment '仕入消費税区分:コード（税区分）'
  , `image` varchar(255) comment '商品代表画像:サムネイル表示にも使用'
  , `description` text comment '商品説明'
  , `video` text comment '商品動画'
  , `expiration_date_note` varchar(200) comment '賞味期限表記'
  , `sales_start_datetime` datetime comment '商品展示開始日時'
  , `sales_end_datetime` datetime comment '商品展示終了日時'
  , `is_published` tinyint(1) default 1 not null comment '公開状態:0：非公開　1：公開'
  , `sale_status` varchar(10) comment '販売ステータス:コード値（販売ステータス）'
  , `stock_management_type` varchar(10) not null comment '在庫管理区分:コード値（在庫管理区分）'
  , `estimated_delivery_date` varchar(10) comment '納期目安:コード（納期目安）'
  , `temperature_control_type` varchar(10) default 1 not null comment '温度管理区分:コード（商品温度管理区分）'
  , `limited_unit_price` decimal(12,2) unsigned comment '限定単価:期間限定の単価'
  , `limited_start_datetime` datetime comment '限定期間開始日時:限定単価の開始日時'
  , `limited_end_datetime` datetime comment '限定期間終了日時:限定単価の終了日時'
  , `delivery_leadtime` int(10) unsigned default 0 not null comment '出荷リードタイム:準備期間'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `goods_PKC` primary key (`id`)
) comment '商品' ;

-- 商品ランク別価格
--* BackupToTempTable
drop table if exists `goods_rank_prices` cascade;

--* RestoreFromTempTable
create table `goods_rank_prices` (
  `goods_id` bigint unsigned not null comment '商品ID'
  , `customer_rank_id` bigint unsigned not null comment '顧客ランクID'
  , `unit_price` decimal(12,2) unsigned default 0 not null comment '単価'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `goods_rank_prices_PKC` primary key (`goods_id`,`customer_rank_id`)
) comment '商品ランク別価格:商品ごとにランク別で単価設定を行う場合に使用する（任意）' ;

-- カテゴリ
--* BackupToTempTable
drop table if exists `categories` cascade;

--* RestoreFromTempTable
create table `categories` (
  `code` varchar(100) not null comment 'カテゴリコード'
  , `name` varchar(100) not null comment 'カテゴリ名'
  , `hierarchy` int(10) unsigned not null comment '階層'
  , `path` text not null comment 'カテゴリパス:ルートからここまでのカテゴリコードを~区切りで連結'
  , `sequence` int(10) unsigned default 100 not null comment '表示順'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `categories_PKC` primary key (`code`)
) comment 'カテゴリ' ;

-- 会員ランク
--* BackupToTempTable
drop table if exists `customer_ranks` cascade;

--* RestoreFromTempTable
create table `customer_ranks` (
  `id` serial not null auto_increment comment 'ID'
  , `rank_name` varchar(100) not null comment 'ランク名'
  , `start_date` date not null comment '適用開始日'
  , `app_member_type` varchar(20) comment 'アプリ会員区分:B-crewのアプリ会員区分'
  , `point_rate` decimal(4,1) comment 'ポイント付与率:パーセンテージ'
  , `discount_type` varchar(10) default 0 not null comment 'ランク別割引区分:コード値（0:割引無し 1:割引額を使用 2:割引率を使用）'
  , `discount_price` decimal(12,2) unsigned comment 'ランク別割引額:値引額'
  , `discount_rate` decimal(4,1) comment 'ランク別割引率:パーセンテージ'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `customer_ranks_PKC` primary key (`id`)
) comment '会員ランク' ;

-- ユーザー
--* BackupToTempTable
drop table if exists `users` cascade;

--* RestoreFromTempTable
create table `users` (
  `id` serial not null auto_increment comment 'ID'
  , `code` varchar(5) comment '社員コード'
  , `name` varchar(100) comment '名前'
  , `email` varchar(255) comment 'メールアドレス'
  , `password` varchar(255) comment 'パスワード'
  , `is_unusable` tinyint(1) default 0 not null comment '利用不可フラグ:0：利用可　1：利用不可'
  , `is_admin` tinyint(1) default 0 not null comment '管理者フラグ:0：一般　1：管理者'
  , `remember_token` varchar(255) comment 'パスワード忘れトークン'
  , `email_verified_at` datetime comment 'メールアドレス認証日時'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `users_PKC` primary key (`id`)
) comment 'ユーザー' ;

-- ユーザー権限
--* BackupToTempTable
drop table if exists `user_auths` cascade;

--* RestoreFromTempTable
create table `user_auths` (
  `user_id` bigint unsigned not null comment 'ユーザーID'
  , `program_cd` varchar(50) not null comment '処理コード'
  , `has_read` tinyint(1) default 0 not null comment '参照:0：権限無し　1：権限有り'
  , `has_update` tinyint(1) default 0 not null comment '追加・更新:0：権限無し　1：権限有り'
  , `has_report_output` tinyint(1) default 0 not null comment '帳票出力:0：権限無し　1：権限有り'
  , `has_disable` tinyint(1) default 0 not null comment '禁止:0：権限無し　1：権限有り'
  , `remark` text comment '備考'
  , constraint `user_auths_PKC` primary key (`user_id`,`program_cd`)
) comment 'ユーザー権限' ;

-- コード値
--* BackupToTempTable
drop table if exists `code_values` cascade;

--* RestoreFromTempTable
create table `code_values` (
  `id` serial not null auto_increment comment 'ID'
  , `code` varchar(5) not null comment 'コード'
  , `key` varchar(10) not null comment 'キー'
  , `value` varchar(100) comment '値'
  , `description` varchar(100) comment '説明'
  , `sequence` int(10) unsigned comment '並び順'
  , `attr_1_description` varchar(100) comment '属性値１説明'
  , `attr_1` varchar(255) comment '属性値１'
  , `attr_2_description` varchar(100) comment '属性値２説明'
  , `attr_2` varchar(255) comment '属性値２'
  , `attr_3_description` varchar(100) comment '属性値３説明'
  , `attr_3` varchar(255) comment '属性値３'
  , `attr_4_description` varchar(100) comment '属性値４説明'
  , `attr_4` varchar(255) comment '属性値４'
  , `attr_5_description` varchar(100) comment '属性値５説明'
  , `attr_5` varchar(255) comment '属性値５'
  , `remark` varchar(100) comment '備考'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `code_values_PKC` primary key (`id`)
) comment 'コード値' ;

create index `idx_codes_code`
  on `code_values`(`code`);

create index `idx_codes_code_value`
  on `code_values`(`code`,`value`);

-- 商品画像
--* BackupToTempTable
drop table if exists `goods_images` cascade;

--* RestoreFromTempTable
create table `goods_images` (
  `goods_id` bigint unsigned not null comment '商品ID'
  , `display_order` smallint unsigned not null comment '表示順'
  , `image` varchar(255) not null comment '商品画像'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `goods_images_PKC` primary key (`goods_id`,`display_order`)
) comment '商品画像:代表画像以外でサムネイルを設定する際に使用する（任意）' ;

-- 受注明細
--* BackupToTempTable
drop table if exists `order_details` cascade;

--* RestoreFromTempTable
create table `order_details` (
  `id` serial not null auto_increment comment 'ID'
  , `order_id` bigint unsigned not null comment '受注ID'
  , `detail_no` smallint unsigned not null comment '明細番号'
  , `order_delivery_id` bigint unsigned not null comment '受注配送先ID'
  , `order_delivery_no` smallint unsigned not null comment '受注配送先番号'
  , `goods_id` bigint unsigned not null comment '商品ID'
  , `goods_code` varchar(20) comment '商品コード'
  , `goods_sku_code` varchar(50) comment '商品SKUコード'
  , `name` varchar(100) comment '商品名'
  , `volume` varchar(100) comment '規格'
  , `jan_code` varchar(20) comment 'JANコード'
  , `maker_id` bigint unsigned comment 'メーカーID'
  , `warehouse_id` bigint unsigned comment '倉庫ID'
  , `tax_kind` varchar(10) not null comment '消費税種類'
  , `tax_type` varchar(10) not null comment '消費税区分'
  , `tax_rate` decimal(4,2) not null comment '消費税率'
  , `tax_rounding_type` varchar(10) not null comment '消費税端数処理区分'
  , `unit_price` decimal(12,2) unsigned default 0 not null comment '単価:税は消費税区分に従う'
  , `sale_price` decimal(12,2) unsigned default 0 not null comment '販売単価（税抜）'
  , `sale_price_tax` decimal(12,2) unsigned default 0 not null comment '販売単価・消費税'
  , `sale_price_tax_included` decimal(12,2) unsigned default 0 not null comment '販売単価（税込）'
  , `discount` decimal(12,2) unsigned default 0 not null comment '商品値引額（税抜）:受注のプロモーション値引額合計に集計'
  , `discount_tax` decimal(12,2) unsigned default 0 not null comment '商品値引額・消費税'
  , `quantity` decimal(10,0) default 0 not null comment '数量'
  , `subtotal` decimal(12,2) unsigned default 0 not null comment '小計（税抜）'
  , `tax` decimal(12,2) unsigned default 0 not null comment '小計消費税'
  , `subtotal_tax_included` decimal(12,2) unsigned default 0 not null comment '小計（税込）'
  , `purchase_unit_price` decimal(12,2) unsigned default 0 not null comment '仕入単価（税抜）'
  , `purchase_tax_kind` varchar(10) not null comment '仕入消費税種類'
  , `purchase_tax_type` varchar(10) not null comment '仕入消費税区分:　'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `order_details_PKC` primary key (`id`)
) comment '受注明細' ;

-- 配送業者
--* BackupToTempTable
drop table if exists `carriers` cascade;

--* RestoreFromTempTable
create table `carriers` (
  `id` serial not null auto_increment comment 'ID'
  , `name` varchar(100) comment '配送業者名'
  , `reference_href` varchar(255) comment '追跡サービスURL:お問い合わせ先URL'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `carriers_PKC` primary key (`id`)
) comment '配送業者' ;

-- 出荷
--* BackupToTempTable
drop table if exists `ships` cascade;

--* RestoreFromTempTable
create table `ships` (
  `id` serial not null auto_increment comment 'ID'
  , `order_id` bigint unsigned not null comment '受注ID'
  , `order_delivery_id` bigint unsigned not null comment '受注配送先ID'
  , `order_delivery_no` smallint unsigned not null comment '受注配送先番号'
  , `status` varchar(10) not null comment '出荷ステータス:コード値'
  , `ship_direct_date` date comment '出荷指示日'
  , `ship_date` date comment '出荷日:配送業者が出荷を行った確定日'
  , `ship_cancel_date` date comment '出荷取消日'
  , `delivery_type` varchar(10) not null comment '配送先区分:コード（配送先区分）'
  , `client_surname` varchar(50) comment '依頼主・姓'
  , `client_name` varchar(50) comment '依頼主・名'
  , `client_surname_kana` varchar(100) comment '依頼主・セイ'
  , `client_name_kana` varchar(100) comment '依頼主・メイ'
  , `client_zip` varchar(7) not null comment '依頼主・郵便番号'
  , `client_prefcode` varchar(2) comment '依頼主・都道府県コード'
  , `client_addr_1` varchar(100) comment '依頼主・住所１:市区町村名'
  , `client_addr_2` varchar(100) comment '依頼主・住所２:番地'
  , `client_addr_3` varchar(100) comment '依頼主・住所３:建物名等'
  , `client_addr` varchar(500) comment '依頼主・住所:都道府県名+住所１+住所２＋住所３'
  , `client_tel` varchar(21) comment '依頼主・電話番号'
  , `ship_surname` varchar(50) comment '出荷先・姓'
  , `ship_name` varchar(50) comment '出荷先・名'
  , `ship_surname_kana` varchar(100) comment '出荷先・セイ'
  , `ship_name_kana` varchar(100) comment '出荷先・メイ'
  , `ship_zip` varchar(7) not null comment '出荷先・郵便番号'
  , `ship_prefcode` varchar(2) comment '出荷先・都道府県コード'
  , `ship_addr_1` varchar(100) comment '出荷先・住所１:市区町村名'
  , `ship_addr_2` varchar(100) comment '出荷先・住所２:番地'
  , `ship_addr_3` varchar(100) comment '出荷先・住所３:建物名等'
  , `ship_addr` varchar(500) comment '出荷先・住所:都道府県名+住所１+住所２＋住所３'
  , `ship_tel` varchar(21) comment '出荷先・電話番号'
  , `carrier_id` bigint unsigned comment '配送業者ID'
  , `desired_delivery_date` date comment '配送希望日'
  , `desired_delivery_time` varchar(10) comment '配送希望時間帯:コード（配送時間帯）'
  , `postage` decimal(12,2) unsigned default 0 not null comment '送料'
  , `payment_fee` decimal(12,2) unsigned default 0 not null comment '決済手数料'
  , `packing_charge` decimal(12,2) unsigned default 0 not null comment '梱包料'
  , `other_fee` decimal(12,2) unsigned default 0 not null comment 'その他手数料'
  , `warehouse_id` bigint unsigned comment '倉庫ID'
  , `warehouse_comment` varchar(100) comment '倉庫向けコメント'
  , `invoice_comment` varchar(100) comment '送り状コメント'
  , `slip_no` varchar(20) comment '問合せ番号'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `ships_PKC` primary key (`id`)
) comment '出荷' ;

-- メール送信ログ
--* BackupToTempTable
drop table if exists `mail_send_logs` cascade;

--* RestoreFromTempTable
create table `mail_send_logs` (
  `id` serial not null auto_increment comment 'ID'
  , `mail_type` varchar(10) not null comment 'メール種別:コード（メール種別）'
  , `customer_id` bigint unsigned comment '顧客ID'
  , `transation_id` bigint unsigned comment 'トランザクションID'
  , `send_at` datetime comment '送信日時'
  , `to` text comment 'To'
  , `from` text comment 'From'
  , `cc` text comment 'cc'
  , `bcc` text comment 'bcc'
  , `title` text comment 'タイトル'
  , `content` text comment '本文'
  , constraint `mail_send_logs_PKC` primary key (`id`)
) comment 'メール送信ログ' ;

-- お気に入り
--* BackupToTempTable
drop table if exists `favorites` cascade;

--* RestoreFromTempTable
create table `favorites` (
  `customer_id` bigint unsigned not null comment '顧客ID'
  , `goods_id` bigint unsigned not null comment '商品ID'
  , constraint `favorites_PKC` primary key (`customer_id`,`goods_id`)
) comment 'お気に入り' ;

-- 郵便番号
--* BackupToTempTable
drop table if exists `zips` cascade;

--* RestoreFromTempTable
create table `zips` (
  `local_cd` varchar(5) comment '全国地方公共団体コード'
  , `zip_cd_5` varchar(5) comment '郵便番号（5桁）'
  , `zip_cd` varchar(7) comment '郵便番号（7桁）'
  , `pref_name_kana` varchar(20) comment '都道府県名（よみ）'
  , `municipality_name_kana` text comment '市区町村名（よみ）'
  , `town_name_kana` text comment '町域名（よみ）'
  , `pref_name` varchar(20) comment '都道府県名'
  , `municipality_name` text comment '市区町村名'
  , `town_name` text comment '町域名'
  , `flag1` int comment '一町域が二以上の郵便番号で表される場合の表示:「1」は該当、「0」は該当せず'
  , `flag2` int comment '小字毎に番地が起番されている町域の表示:「1」は該当、「0」は該当せず'
  , `flag3` int comment '丁目を有する町域の場合の表示:「1」は該当、「0」は該当せず'
  , `flag4` int comment '一つの郵便番号で二以上の町域を表す場合の表示:「1」は該当、「0」は該当せず'
  , `flag5` int comment '更新の表示:「0」は変更なし、「1」は変更あり、「2」廃止（廃止データのみ使用）'
  , `flag6` int comment '変更理由:「0」は変更なし、「1」市政・区政・町政・分区・政令指定都市施行、「2」住居表示の実施、「3」区画整理、「4」郵便区調整等、「5」訂正、「6」廃止（廃止データのみ使用）'
) comment '郵便番号' ;

-- 消費税
--* BackupToTempTable
drop table if exists `taxes` cascade;

--* RestoreFromTempTable
create table `taxes` (
  `id` serial not null auto_increment comment 'ID'
  , `tax_kind` varchar(10) not null comment '消費税種類:コード（税種類）'
  , `tax_rate` decimal(4,2) not null comment '消費税率'
  , `start_date` date not null comment '適用開始日'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `taxes_PKC` primary key (`id`)
) comment '消費税' ;

-- 受注配送先
--* BackupToTempTable
drop table if exists `order_deliveries` cascade;

--* RestoreFromTempTable
create table `order_deliveries` (
  `id` serial not null auto_increment comment 'ID'
  , `order_id` bigint unsigned not null comment '受注ID'
  , `delivery_no` smallint unsigned not null comment '配送先番号:注文内連番'
  , `delivery_type` varchar(10) not null comment '配送先区分:コード（配送先区分）'
  , `client_surname` varchar(50) comment '依頼主・姓'
  , `client_name` varchar(50) comment '依頼主・名'
  , `client_surname_kana` varchar(100) comment '依頼主・セイ'
  , `client_name_kana` varchar(100) comment '依頼主・メイ'
  , `client_zip` varchar(7) not null comment '依頼主・郵便番号'
  , `client_prefcode` varchar(2) comment '依頼主・都道府県コード'
  , `client_addr_1` varchar(100) comment '依頼主・住所１:市区町村名'
  , `client_addr_2` varchar(100) comment '依頼主・住所２:番地'
  , `client_addr_3` varchar(100) comment '依頼主・住所３:建物名等'
  , `client_addr` varchar(500) comment '依頼主・住所:都道府県名+住所１+住所２＋住所３'
  , `client_tel` varchar(21) comment '依頼主・電話番号'
  , `delivery_surname` varchar(50) comment '配送先・姓'
  , `delivery_name` varchar(50) comment '配送先・名'
  , `delivery_surname_kana` varchar(100) comment '配送先・セイ'
  , `delivery_name_kana` varchar(100) comment '配送先・メイ'
  , `delivery_zip` varchar(7) not null comment '配送先・郵便番号'
  , `delivery_prefcode` varchar(2) comment '配送先・都道府県コード'
  , `delivery_addr_1` varchar(100) comment '配送先・住所１:市区町村名'
  , `delivery_addr_2` varchar(100) comment '配送先・住所２:番地'
  , `delivery_addr_3` varchar(100) comment '配送先・住所３:建物名等'
  , `delivery_addr` varchar(500) comment '配送先・住所:都道府県名+住所１+住所２＋住所３'
  , `delivery_tel` varchar(21) comment '配送先・電話番号'
  , `carrier_id` bigint unsigned not null comment '配送業者ID'
  , `delivery_date` date comment '配送希望日'
  , `delivery_time` varchar(10) comment '配送希望時間帯:コード値'
  , `postage` decimal(12,2) unsigned default 0 not null comment '送料'
  , `payment_fee` decimal(12,2) unsigned default 0 not null comment '決済手数料'
  , `packing_charge` decimal(12,2) unsigned default 0 comment '梱包料'
  , `other_fee` decimal(12,2) unsigned default 0 not null comment 'その他手数料'
  , `warehouse_comment` varchar(100) comment '倉庫向けコメント'
  , `invoice_comment` varchar(100) comment '送り状コメント'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `order_deliveries_PKC` primary key (`id`)
) comment '受注配送先' ;

-- メニュー
--* BackupToTempTable
drop table if exists `menus` cascade;

--* RestoreFromTempTable
create table `menus` (
  `id` serial not null auto_increment comment 'ID'
  , `name` varchar(50) comment 'メニュー名'
  , `href` varchar(255) comment '遷移先'
  , `icon` varchar(255) comment 'icon class名'
  , `slug` varchar(255) comment 'アイテム名:title, link, dropdown'
  , `title_id` bigint unsigned comment '親titleid'
  , `parent_id` bigint unsigned comment '親メニューid:dropdown時の親'
  , `program_cd` varchar(50) comment '処理コード'
  , `sequence` int(10) unsigned comment '順序'
  , constraint `menus_PKC` primary key (`id`)
) comment 'メニュー' ;

-- 出荷明細
--* BackupToTempTable
drop table if exists `ship_details` cascade;

--* RestoreFromTempTable
create table `ship_details` (
  `id` serial not null auto_increment comment 'ID'
  , `ship_id` bigint unsigned not null comment '出荷ID'
  , `detail_no` smallint unsigned not null comment '明細NO'
  , `order_id` bigint unsigned not null comment '受注ID'
  , `order_delivery_id` bigint unsigned not null comment '受注配送先ID'
  , `order_delivery_no` smallint unsigned not null comment '受注配送先番号'
  , `order_detail_id` bigint unsigned not null comment '受注明細ID'
  , `order_detail_no` smallint unsigned not null comment '受注明細番号'
  , `goods_id` bigint unsigned comment '商品ID'
  , `goods_code` varchar(20) comment '商品コード'
  , `name` varchar(100) comment '商品名'
  , `volume` varchar(100) comment '規格'
  , `jan_code` varchar(20) comment 'JANコード'
  , `maker_id` bigint unsigned comment 'メーカーID'
  , `tax_kind` varchar(10) not null comment '消費税種類'
  , `tax_type` varchar(10) not null comment '消費税区分'
  , `tax_rate` decimal(4,2) not null comment '消費税率'
  , `tax_rounding_type` varchar(10) not null comment '消費税端数処理区分'
  , `unit_price` decimal(12,2) unsigned default 0 not null comment '単価'
  , `sale_price` decimal(12,2) unsigned default 0 not null comment '販売単価（税抜）:税は消費税区分に従う'
  , `sale_price_tax` decimal(12,2) unsigned default 0 not null comment '販売単価・消費税'
  , `sale_price_tax_included` decimal(12,2) unsigned default 0 not null comment '販売単価（税込）'
  , `discount` decimal(12,2) unsigned default 0 not null comment '商品値引額（税抜）'
  , `discount_tax` decimal(12,2) unsigned default 0 not null comment '商品値引額・消費税'
  , `quantity` decimal(10,0) default 0 not null comment '数量'
  , `subtotal` decimal(10,0) default 0 not null comment '小計（税抜）'
  , `tax` decimal(12,2) unsigned default 0 not null comment '消費税'
  , `subtotal_tax_included` decimal(12,2) unsigned default 0 not null comment '小計（税込）'
  , `purchase_unit_price` decimal(12,2) unsigned default 0 not null comment '仕入単価（税抜）'
  , `purchase_tax_kind` varchar(10) not null comment '仕入消費税種類'
  , `purchase_tax_type` varchar(10) not null comment '仕入消費税区分'
  , `created_by` bigint unsigned default 0 not null comment '登録者ID'
  , `created_at` datetime default current_timestamp not null comment '登録日時'
  , `updated_by` bigint unsigned default 0 not null comment '更新者ID'
  , `updated_at` datetime default current_timestamp on update current_timestamp not null comment '更新日時'
  , `is_deleted` tinyint default 0 not null comment '削除フラグ'
  , constraint `ship_details_PKC` primary key (`id`)
) comment '出荷明細' ;

-- 口割れ
--* BackupToTempTable
drop table if exists `split_deliveries` cascade;
