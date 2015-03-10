<?php

namespace app\controllers;

use yii;
use yii\web\Controller;

class ColorHexController extends Controller
{
    public $enableCsrfValidation = false;

    public function init()
    {

    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}