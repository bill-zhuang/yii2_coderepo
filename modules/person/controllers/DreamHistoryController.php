<?php

namespace app\modules\person\controllers;

use yii;
use yii\web\Controller;
use app\modules\person\models\DreamHistory;
use yii\filters\AccessControl;

class DreamHistoryController extends Controller
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
                            'index',
                            'add-dream-history',
                            'modify-dream-history',
                            'delete-dream-history',
                            'get-dream-history',
                        ],
                        'roles' => ['@'],
                    ],
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
            'dh_status' => [
                'compare_type' => '=',
                'value' => yii::$app->params['valid_status']
            ]
        ];
        $order_by = ['dh_create_time' => SORT_DESC];
        $total = DreamHistory::getDreamHistoryCount($conditions);
        $data = DreamHistory::getDreamHistoryData($conditions, $page_length, $start, $order_by);

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

    public function actionAddDreamHistory()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['dream_history_date']))
        {
            try
            {
                $dream_history = new DreamHistory();
                $dream_history->dh_happen_date = trim(yii::$app->request->post('dream_history_date'));
                $dream_history->dh_count = intval(yii::$app->request->post('dream_history_count'));
                $dream_history->dh_status = yii::$app->params['valid_status'];
                $dream_history->dh_create_time = date('Y-m-d H:i:s');
                $dream_history->dh_update_time = date('Y-m-d H:i:s');
                $affected_rows = intval($dream_history->save());
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
            }
        }

        echo json_encode($affected_rows);
        exit;
    }

    public function actionModifyDreamHistory()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['dream_history_dh_id']))
        {
            try
            {
                $affected_rows = $this->_updateDreamHistory();
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
            }
        }
        
        echo json_encode($affected_rows);
        exit;
    }

    public function actionDeleteDreamHistory()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['dh_id']))
        {
            try
            {
                $dh_id = intval(yii::$app->request->post('dh_id'));
                $dream_history = DreamHistory::findOne($dh_id);
                if ($dream_history instanceof DreamHistory)
                {
                    $dream_history->dh_status = yii::$app->params['invalid_status'];
                    $dream_history->dh_update_time = date('Y-m-d H:i:s');
                    $affected_rows = intval($dream_history->save());
                }
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
            }
        }

        echo json_encode($affected_rows);
        exit;
    }

    public function actionGetDreamHistory()
    {
        $data = [];
        if (isset($_GET['dh_id']))
        {
            $dh_id = intval(yii::$app->request->get('dh_id'));
            if ($dh_id > yii::$app->params['invalid_primary_id'])
            {
                $data = DreamHistory::getDreamHistoryByID($dh_id);
            }
        }

        echo json_encode($data);
        exit;
    }

    private function _updateDreamHistory()
    {
        $dh_id = intval(yii::$app->request->post('dream_history_dh_id'));
        $old_data = DreamHistory::findOne($dh_id);

        $old_data->dh_happen_date = trim(yii::$app->request->post('dream_history_date'));
        $old_data->dh_count = intval(yii::$app->request->post('dream_history_count'));
        $old_data->dh_update_time = date('Y-m-d H:i:s');

        return $old_data->save();
    }
}