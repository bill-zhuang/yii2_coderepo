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
        'js/default/modify-password.js',
        'js/common/jAjaxWidget.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\jQuerySerializeObjectAsset',
    ];
}