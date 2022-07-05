<?php
use App\Helpers\Util\ArrayHelper;

require(config_path('admin/page/app-page-settings.php'));
require(config_path('admin/simple-template/app-simple-template-settings.php'));
/*
|--------------------------------------------------------------------------
| Admin Guardのアプリケーションの設定
|--------------------------------------------------------------------------
*/
$adminAppSettings = [
    'page' => $adminPageSettings,
    'simple-template' => $adminSimpleTemplateSettings
];

$appSettings = config('app-settings');
return ArrayHelper::array_merge_for_nest($appSettings, $adminAppSettings);
