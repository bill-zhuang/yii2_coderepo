<?php

namespace app\assets\person;

use yii\web\AssetBundle;
class AppAssetFinancePayment extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'js/person/finance-payment/finance-payment.js',
        'js/common/datetime-picker.js',
        'js/common/alertInfo.js',
        'js/common/util.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\AssetDatetimePicker',
        'app\assets\plugins\PaginationAsset',
        'app\assets\plugins\AssetBootstrapSelect',
    ];
}