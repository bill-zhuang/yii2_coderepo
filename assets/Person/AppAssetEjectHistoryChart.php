<?php

namespace app\assets\person;

use yii\web\AssetBundle;
class AppAssetEjectHistoryChart extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/public/jAjaxWidget.js',
        'js/public/datetimepicker.js',
        'js/person/eject-history-chart.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\HighChartsAsset',
        'app\assets\plugins\jQuerySerializeObjectAsset',
        'app\assets\plugins\AssetDatetimePicker',
    ];
}