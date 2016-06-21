<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class IoniconsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/driftyco/ionicons';
    public $baseUrl = '@web';
    public $css = [
        'css/ionicons.css',
    ];
    public $js = [

    ];
}