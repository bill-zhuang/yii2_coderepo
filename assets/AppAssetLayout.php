<?php
namespace app\assets;

use yii\web\AssetBundle;
class AppAssetLayout extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/common.css',
        'css/layout.css',
    ];
    public $js = [
        'js/common/layout.js',
        'js/common/alertInfo.js',
        'js/common/common.js',
        'js/common/util.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\jQueryCookieAsset',
    ];
}
