<?php

namespace app\assets;

use yii\web\AssetBundle;
class AppAssetColorHex extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/color-hex/farbtastic.css',
    ];
    public $js = [
        'js/color-hex/farbtastic.js',
        'js/color-hex/color-hex.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\jQueryMiniColorsAsset',
    ];
}