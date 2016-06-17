<?php

namespace app\assets\person;

use yii\web\AssetBundle;
class AppAssetGrainRecycleHistoryChart extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/public/jAjaxWidget.js',
        'js/public/regexWidget.js',
        'js/public/alertMessage.js',
        'js/public/datetimepicker.js',
        'js/public/pagination.js',
        'js/person/grain-recycle-history-chart.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\HighChartsAsset',
        'app\assets\plugins\jQuerySerializeObjectAsset',
        'app\assets\plugins\AssetDatetimePicker',
    ];
}