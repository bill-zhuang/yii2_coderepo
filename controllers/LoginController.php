<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Auth;
use yii\filters\AccessControl;

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
        if (!Yii::$app->user->isGuest)
        {
            //return $this->goHome();
            return $this->redirect('/index.php/main/index');
        }

        if (Yii::$app->request->isPost)
        {
            $auth = new Auth();
            if ($auth->load(Yii::$app->request->post()) && $auth->login())
            {
                //return $this->goBack();
                return $this->redirect('/index.php/main/index');
            }
            else
            {
                return $this->render('login', ['content' => '帐号或密码错误']);
            }
        }

        return $this->render('login', ['content' => '']);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('/index.php/login/login');
    }

}