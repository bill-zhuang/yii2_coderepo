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
        'js/public/jAjaxWidget.js',
        'js/public/regexWidget.js',
        'js/public/alertMessage.js',
        'js/public/pagination.js',
        'js/person/finance-category.js',
        'js/person/load-finance-category.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\PaginationAsset',
        'app\assets\plugins\jQuerySerializeObjectAsset',
    ];
}