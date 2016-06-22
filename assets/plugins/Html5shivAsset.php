<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
use yii\web\View;
class Html5shivAsset extends AssetBundle
{
    public $sourcePath = '@vendor/afarkas/html5shiv';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'dist/html5shiv.min.js',
    ];
    public $jsOptions = [
        'condition' => 'lt IE 9',
        'position' => View::POS_HEAD,
    ];
}