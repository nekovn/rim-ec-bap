<?php
use App\Helpers\Util\ArrayHelper;

require(config_path('member/page/app-page-settings.php'));

/*
|--------------------------------------------------------------------------
| Member Guardのアプリケーションの設定
|--------------------------------------------------------------------------
*/
$memberAppSettings = [
  'page' => $memberPageSettings,
];

$appSettings = config('app-settings');
return ArrayHelper::array_merge_for_nest($appSettings, $memberAppSettings);
