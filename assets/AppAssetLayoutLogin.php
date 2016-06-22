<?php
namespace app\assets;

use yii\web\AssetBundle;
class AppAssetLayoutLogin extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/login.css',
    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\Html5shivAsset',
        'app\assets\plugins\RespondAsset',
    ];
}
