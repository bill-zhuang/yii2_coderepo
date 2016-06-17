<?php

namespace app\assets;

use yii\web\AssetBundle;
class AppAssetBackendLog extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/public/jAjaxWidget.js',
        'js/public/alertMessage.js',
        'js/public/pagination.js',
        'js/default/backend-log.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\PaginationAsset',
        'app\assets\plugins\jQuerySerializeObjectAsset',
    ];
}