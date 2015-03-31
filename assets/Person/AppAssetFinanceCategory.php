<?php

namespace app\assets\person;

use yii\web\AssetBundle;
class AppAssetFinanceCategory extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        
    ];
    public $js = [
        'js/pagination/jquery.twbsPagination.min.js',
        'js/person/finance-category/finance-category.js',
        'js/common/alertInfo.js',
        'js/common/util.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
    ];
}