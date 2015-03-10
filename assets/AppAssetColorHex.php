<?php

namespace app\assets;

use yii\web\AssetBundle;
class AppAssetColorHex extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/color-hex/farbtastic.css',
        'css/color-hex/minicolor.css',
    ];
    public $js = [
        'js/color-hex/farbtastic.js',
        'js/color-hex/minicolor.min.js',
        'js/color-hex/color-hex.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
    ];
}