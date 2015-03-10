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
        'js/google-map/marker-cluster.js',
        'js/google-map/multiple-location.js',
        'js/google-map/gmaprepo.js',
        'http://maps.googleapis.com/maps/api/js?key=AIzaSyCefZle2DqxF9i51PTfoZsZoOmvWzKYhF4&sensor=true',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
    ];
}