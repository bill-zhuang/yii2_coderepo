<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class PaginationAsset extends AssetBundle
{
    public $sourcePath = 'js/pagination';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'jquery.twbsPagination.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset', //jQuery
    ];
}