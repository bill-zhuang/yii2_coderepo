<?php

namespace app\assets\plugins;

use yii\web\AssetBundle;
use yii\web\View;
class RespondAsset extends AssetBundle
{
    public $sourcePath = '@vendor/rogeriopradoj/respond';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'dest/respond.min.js',
    ];
    public $jsOptions = [
        'condition' => 'lt IE 9',
        'position' => View::POS_HEAD,
    ];
}