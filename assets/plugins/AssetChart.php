<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class AssetChart extends AssetBundle
{
    public $sourcePath = '@vendor/nnnick/chartjs';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'Chart.min.js',
    ];
}