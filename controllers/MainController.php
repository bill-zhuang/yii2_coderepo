<?php

namespace app\controllers;

use app\library\bill\Constant;
use app\models\User;
use yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class MainController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'modify-password'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionModifyPassword()
    {
        if (yii::$app->request->isPost) {
            $userID = Yii::$app->getUser()->getId();
            $params = yii::$app->request->get('params', array());
            $oldPassword = isset($params['old_password']) ? addslashes($params['old_password']) : '';
            $newPassword = isset($params['new_password']) ? addslashes($params['new_password']) : '';
            if ($userID !== null) {
                $affectedRows = Constant::INVALID_PRIMARY_ID;
                $user = User::findIdentity($userID);
                if ($user->validatePassword($oldPassword)) {
                    $user->setPassword($newPassword);
                    $user->bu_update_time = date('Y-m-d H:i:s');
                    $affectedRows = $user->save();
                }
                if ($affectedRows > 0) {
                    Yii::$app->user->logout();
                    //redirect to login page & set layout to login
                    $this->layout = 'layout-login';
                    return $this->render('@app/views/login/login', ['content' => '修改成功!请登录!']);
                } else {
                    $content = '修改失败！';
                    return $this->render('index', ['content' => $content]);
                }
            }
        }

        return $this->render('modify-password');
    }
}