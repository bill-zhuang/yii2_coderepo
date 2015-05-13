<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class AssetDatetimePicker extends AssetBundle
{
    public $sourcePath = '@vendor/components/bootstrap-datetimepicker';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap-datetimepicker.min.css'
    ];
    public $js = [
        'js/bootstrap-datetimepicker.min.js',
        'js/locales/bootstrap-datetimepicker.zh-CN.js',
    ];
}