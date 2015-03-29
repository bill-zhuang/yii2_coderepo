<?php

namespace app\assets\person;

use yii\web\AssetBundle;
class AppAssetDreamHistoryChart extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/person/dream-history-chart/dream-history-chart.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\AssetChart',
    ];
}