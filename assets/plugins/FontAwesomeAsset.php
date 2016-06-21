<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/fortawesome/font-awesome';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome.css',
    ];
    public $js = [

    ];
}