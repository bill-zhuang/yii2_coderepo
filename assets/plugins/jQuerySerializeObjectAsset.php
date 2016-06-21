<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class jQuerySerializeObjectAsset extends AssetBundle
{
    public $sourcePath = 'js/jquery-serialize-object/js';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'jquery.serialize-object.min.js',
    ];
}