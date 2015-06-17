<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class AssetBootstrapSelect extends AssetBundle
{
    public $sourcePath = '@vendor/bootstrap-select/bootstrap-select/dist';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap-select.min.css'
    ];
    public $js = [
        'js/bootstrap-select.min.js',
    ];
}