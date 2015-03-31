<?php

namespace app\assets\person;

use yii\web\AssetBundle;
class AppAssetDreamHistory extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/person/dream-history/dream-history.js',
        'js/common/alertInfo.js',
        'js/common/util.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\AssetDatetimePicker',
        'app\assets\plugins\PaginationAsset',
    ];
}