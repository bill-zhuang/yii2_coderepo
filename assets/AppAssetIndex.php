<?php

namespace app\assets;

use yii\web\AssetBundle;
class AppAssetIndex extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/public/jAjaxWidget.js',
        'js/index/index.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
    ];
}