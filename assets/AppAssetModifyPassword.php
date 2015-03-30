<?php

namespace app\assets;

use yii\web\AssetBundle;
class AppAssetModifyPassword extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/main/modify-password.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
    ];
}