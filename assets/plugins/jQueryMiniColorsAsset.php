<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class jQueryMiniColorsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/abeautifulsite/jquery-minicolors';
    public $baseUrl = '@web';
    public $css = [
        'jquery.minicolors.css'
    ];
    public $js = [
        'jquery.minicolors.min.js',
    ];
}