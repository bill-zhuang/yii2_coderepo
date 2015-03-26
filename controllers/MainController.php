<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\BackendUser;
use app\models\User;
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
        //var_dump(yii::$app->getRequest()->getCookies());
        return $this->render('index');
    }

    public function actionModifyPassword()
    {
        if (yii::$app->request->isPost)
        {
            //todo get user name
            $user_name = \Yii::$app->session->get('user_info')['bu_name'];
            $old_password = addslashes(yii::$app->request->post('old_password'));
            $new_password = addslashes(yii::$app->request->post('new_password'));
            $user_info = User::findByUsername($user_name);
            if ($user_info !== null)
            {
                if ($user_info['bu_password'] !== md5($old_password))
                {
                    $content = '原密码错误！';
                }
                else
                {
                    $where = [
                        'bu_name' => $user_name,
                        'bu_status' => yii::$app->params['valid_status']
                    ];
                    $update_data = [
                        'bu_password' => md5($new_password),
                        'bu_update_time' => date('Y-m-d H:i:s')
                    ];
                    $affect_rows = BackendUser::updateAll($update_data, $where);
                    if ($affect_rows > 0)
                    {
                        //todo login with new password
                        //User::login($user_name, $new_password);
                        Yii::$app->user->logout();
                        $content = '修改成功！';
                    }
                    else
                    {
                        $content = '修改失败！';
                    }
                }

                return $this->render('index', ['content' => $content]);
            }
        }

        return $this->render('modify-password');
    }

}