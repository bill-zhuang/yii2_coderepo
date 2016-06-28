<?php

namespace app\controllers;

use yii;

class ColorHexController extends BillController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }
}