<?php

namespace app\assets\person;

use yii\web\AssetBundle;
class AppAssetGrainRecycleHistory extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/public/jAjaxWidget.js',
        'js/public/regexWidget.js',
        'js/public/dateWidget.js',
        'js/public/alertMessage.js',
        'js/public/pagination.js',
        'js/public/datetimepicker.js',
        'js/person/grain-recycle-history.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\AssetDatetimePicker',
        'app\assets\plugins\PaginationAsset',
        'app\assets\plugins\jQuerySerializeObjectAsset',
    ];
}