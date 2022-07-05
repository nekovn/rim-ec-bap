
use bap_ec_dev;

CREATE USER bapec_user IDENTIFIED BY 'elseif2021';

GRANT all ON bap_ec_dev.* TO bapec_user;
