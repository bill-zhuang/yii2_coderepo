<?php

namespace app\controllers;

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
        if (yii::$app->request->isPost)
        {
            $user_id = Yii::$app->getUser()->getId();
            $old_password = addslashes(yii::$app->request->post('old_password'));
            $new_password = addslashes(yii::$app->request->post('new_password'));
            if ($user_id !== null)
            {
                $affected_rows = 0;
                $user = User::findOne($user_id);
                if ($user->validatePassword($old_password))
                {
                    $user->setPassword($new_password);
                    $user->bu_update_time = date('Y-m-d H:i:s');
                    $affected_rows = $user->save();
                }
                if ($affected_rows > 0)
                {
                    Yii::$app->user->logout();
                    //redirect to login page & set layout to login
                    $this->layout = 'layout-login';
                    return $this->render('@app/views/login/login', ['content' => '修改成功!请登录!']);
                }
                else
                {
                    $content = '修改失败！';
                    return $this->render('index', ['content' => $content]);
                }
            }
        }

        return $this->render('modify-password');
    }

}