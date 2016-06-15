<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class HighChartsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/miloschuman/yii2-highcharts-widget/src/assets';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'highcharts.src.js',
    ];
}