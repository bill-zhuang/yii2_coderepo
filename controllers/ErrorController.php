<?php

namespace app\controllers;

use Yii;
use app\library\bill\Util;

class ErrorController extends BillController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $exception = Yii::$app->errorHandler->exception;
        Util::handleException($exception, 'Error from trigger error/index');
        return $this->render('index');
    }

    public function actionNoPermission()
    {
        return $this->render('no-permission');
    }

}
