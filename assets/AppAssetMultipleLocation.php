<?php

namespace app\assets;

use yii\web\AssetBundle;
class AppAssetMultipleLocation extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/public/jAjaxWidget.js',
        'js/default/marker-cluster.js',
        'js/default/multiple-location.js',
        'js/default/gmaprepo.js',
        'http://maps.googleapis.com/maps/api/js?key=AIzaSyCefZle2DqxF9i51PTfoZsZoOmvWzKYhF4&sensor=true',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
    ];
}