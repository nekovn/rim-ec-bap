
-- DB 作成
CREATE DATABASE IF NOT EXISTS bap_ec_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- DB切り替え
use bap_ec_dev;

-- ユーザー作成
CREATE USER bapec_user IDENTIFIED BY 'elseif2021';

GRANT all ON bap_ec_dev.* TO bapec_user;
