<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class AssetChart extends AssetBundle
{
    public $sourcePath = '@bower/chart.js';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'Chart.min.js',
    ];
}