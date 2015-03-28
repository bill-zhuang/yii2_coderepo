<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\BackendUser;
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
                $where = [
                    'bu_id' => $user_id,
                    'bu_password' => md5($old_password),
                    'bu_status' => yii::$app->params['valid_status']
                ];
                $update_data = [
                    'bu_password' => md5($new_password),
                    'bu_update_time' => date('Y-m-d H:i:s')
                ];
                $affect_rows = BackendUser::updateAll($update_data, $where);
                if ($affect_rows > 0)
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