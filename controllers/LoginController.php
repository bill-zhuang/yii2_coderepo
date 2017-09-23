<?php

namespace app\controllers;

use yii;
use app\models\Auth;
use app\library\bill\JsMessage;
use yii\web\Response;

class LoginController extends BillController
{
    public $enableCsrfValidation = false;
    public $layout = 'layout-login';
    public $defaultAction = 'login';

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/main/index');
        }

        if (Yii::$app->request->isPost) {
            $auth = new Auth();
            if ($auth->load(Yii::$app->request->post(), 'params') && $auth->login()) {
                //return $this->goBack();
                $redirectUrl = isset($params['location']) ? urldecode($params['location']) : '';
                if ($redirectUrl === '' || $redirectUrl === '/') {
                    $redirectUrl = '/main/index';
                }
                $jsonArray['data'] = [
                    //'redirectUrl' => '/main/index',
                    'redirectUrl' => $redirectUrl,
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
        return $this->redirect('/login/login');
    }

}