<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
class AssetCKEditor extends AssetBundle
{
    public $sourcePath = '@vendor/ckeditor/ckeditor';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'ckeditor.js',
    ];
}