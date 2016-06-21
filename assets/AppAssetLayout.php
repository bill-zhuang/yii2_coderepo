<?php
namespace app\assets;

use yii\web\AssetBundle;
class AppAssetLayout extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/common.css',
        'css/scroll-up.css',
    ];
    public $js = [
        'js/layout.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\jQueryCookieAsset',
        'app\assets\plugins\FontAwesomeAsset',
        'app\assets\plugins\IoniconsAsset',
        'app\assets\plugins\AdminLTEAsset',
    ];
}
