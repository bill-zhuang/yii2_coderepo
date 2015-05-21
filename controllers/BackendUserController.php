<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\BackendUser;
use app\models\User;

class BackendUserController extends Controller
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
                            'actions' => [
                                'index', 'add-backend-user', 'reset-password', 'get-backend-user', 'delete-backend-user'
                            ],
                        ],
                    'roles' => ['@'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $current_page = intval(yii::$app->request->get('current_page', yii::$app->params['init_start_page']));
        $page_length = intval(yii::$app->request->get('page_length', yii::$app->params['init_page_length']));
        $start = ($current_page - yii::$app->params['init_start_page']) * $page_length;
        $keyword = trim(yii::$app->request->get('keyword', ''));

        $conditions = [
            'bu_status' => [
                'compare_type' => '=',
                'value' => yii::$app->params['valid_status']
            ]
        ];
        $order_by = ['bu_id' => SORT_DESC];
        $total = BackendUser::getBackendUserCount($conditions);
        $data = BackendUser::getBackendUserData($conditions, $page_length, $start, $order_by);

        $js_data = [
            'current_page' => $current_page,
            'page_length' => $page_length,
            'total_pages' => ceil($total / $page_length) ? ceil($total / $page_length) : yii::$app->params['init_total_page'],
            'total' => $total,
            'start' => $start,
            'keyword' => $keyword,
        ];
        $view_data = [
            'data' => $data,
            'js_data' => $js_data,
        ];
        return $this->render('index', $view_data);
    }

    public function actionAddBackendUser()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['backend_user_bu_name']))
        {
            try
            {
                $user_name = trim(yii::$app->request->post('backend_user_bu_name'));
                if (User::findByUsername($user_name) === null)
                {
                    $user = new User();
                    $user->bu_name = $user_name;
                    $user->setPassword(yii::$app->params['init_password']);
                    $user->generateAuthKey();
                    $user->bu_role = 1;
                    $user->bu_status = yii::$app->params['valid_status'];
                    $user->bu_create_time = date('Y-m-d H:i:s');
                    $user->bu_update_time = date('Y-m-d H:i:s');
                    $affected_rows = intval($user->save());
                }
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
                $affected_rows = yii::$app->params['init_affected_rows'];
            }
        }

        echo json_encode($affected_rows);
        exit;
    }

    public function actionResetPassword()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['bu_id']))
        {
            try
            {
                $bu_id = intval(yii::$app->request->post('bu_id'));
                $user = User::findIdentity($bu_id);
                $user->setPassword(yii::$app->params['init_password']);
                $user->generateAuthKey();
                $user->bu_update_time = date('Y-m-d H:i:s');
                $affected_rows = intval($user->save());
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
            }
        }

        echo json_encode($affected_rows);
        exit;
    }

    public function actionDeleteBackendUser()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['bu_id']))
        {
            try
            {
                $bu_id = intval(yii::$app->request->post('bu_id'));
                $user = User::findIdentity($bu_id);
                $user->bu_status = yii::$app->params['invalid_status'];
                $user->bu_update_time = date('Y-m-d H:i:s');
                $affected_rows = intval($user->save());
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
            }
        }

        echo json_encode($affected_rows);
        exit;
    }

    public function actionGetBackendUser()
    {
        $data = [];
        if (isset($_GET['bu_id']))
        {
            $bu_id = intval(yii::$app->request->get('bu_id'));
            if ($bu_id > yii::$app->params['invalid_primary_id'])
            {
                $data = BackendUser::getBackendUserByID($bu_id);
            }
        }

        echo json_encode($data);
        exit;
    }

}