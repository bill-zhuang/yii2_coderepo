<?php

namespace app\assets;

use yii\web\AssetBundle;
class AppAssetBackendUser extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/common/alertInfo.js',
        'js/common/util.js',
        'js/backend-user/backend-user.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\PaginationAsset',
    ];
}