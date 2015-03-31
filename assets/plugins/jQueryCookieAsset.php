<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class jQueryCookieAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-cookie';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'jquery.cookie.js',
    ];
}