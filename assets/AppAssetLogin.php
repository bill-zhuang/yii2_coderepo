<?php

namespace app\assets;

use yii\web\AssetBundle;
class AppAssetLogin extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/common.js',
    ];
    public $js = [
        'js/public/jAjaxWidget.js',
        'js/default/login.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\jQueryCookieAsset',
        'app\assets\plugins\jQuerySerializeObjectAsset',
    ];
}