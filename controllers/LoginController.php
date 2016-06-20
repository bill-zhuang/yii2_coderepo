<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Auth;
use yii\filters\AccessControl;
use app\library\bill\JsMessage;
use yii\web\Response;

class LoginController extends Controller
{
    public $enableCsrfValidation = false;
    public $layout = 'layout-login';
    public $defaultAction = 'login';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/index.php/main/index');
        }

        if (Yii::$app->request->isPost) {
            $auth = new Auth();
            if ($auth->load(Yii::$app->request->post(), 'params') && $auth->login()) {
                //return $this->goBack();
                $jsonArray['data'] = [
                    'redirectUrl' => '/index.php/main/index',
                ];
            } else {
                $jsonArray['error'] = [
                    'message' => JsMessage::ACCOUNT_PASSWORD_ERROR,
                ];
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $jsonArray;
        }

        return $this->render('login');
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('/index.php/login/login');
    }

}