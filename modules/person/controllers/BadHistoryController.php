<?php

namespace app\modules\person\controllers;

use yii;
use yii\web\Controller;
use app\modules\person\models\BadHistory;

class BadHistoryController extends Controller
{
    public $enableCsrfValidation = false;

    public function init()
    {

    }

    public function actionIndex()
    {
        $current_page = intval(yii::$app->request->get('current_page', yii::$app->params['init_start_page']));
        $page_length = intval(yii::$app->request->get('page_length', yii::$app->params['init_page_length']));
        $start = ($current_page - yii::$app->params['init_start_page']) * $page_length;
        $keyword = trim(yii::$app->request->get('keyword', ''));

        $conditions = [
            'bh_status' => [
                'compare_type' => '=',
                'value' => yii::$app->params['valid_status']
            ]
        ];
        $order_by = 'bh_create_time desc';
        $total = BadHistory::getBadHistoryCount($conditions);
        $data = BadHistory::getBadHistoryData($conditions, $page_length, $start, $order_by);

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

    public function actionAddBadHistory()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['bad_history_date']))
        {
            $transaction = BadHistory::getDb()->beginTransaction();
            try
            {
                $affected_rows = $this->_addBadHistory();
                $transaction->commit();
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
                $transaction->rollBack();
            }
        }

        echo json_encode($affected_rows);
        exit;
    }

    public function actionModifyBadHistory()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['bad_history_bh_id']))
        {
            $transaction = BadHistory::getDb()->beginTransaction();
            try
            {
                $affected_rows = $this->_updateBadHistory();
                $transaction->commit();
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
                $transaction->rollBack();
            }
        }
        
        echo json_encode($affected_rows);
        exit;
    }

    public function actionDeleteBadHistory()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['bh_id']))
        {
            $transaction = BadHistory::getDb()->beginTransaction();
            try
            {
                $bh_id = intval(yii::$app->request->post('bh_id'));
                $update_data = [
                    'bh_status' => yii::$app->params['invalid_status'],
                    'bh_update_time' => date('Y-m-d H:i:s'),
                ];
                $where = [
                    'bh_id' => $bh_id,
                ];
                $affected_rows = BadHistory::updateAll($update_data, $where);
                $transaction->commit();
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
                $transaction->rollBack();
            }
        }

        echo json_encode($affected_rows);
        exit;
    }

    public function actionGetBadHistory()
    {
        $data = [];
        if (isset($_GET['bh_id']))
        {
            $bh_id = intval(yii::$app->request->get('bh_id'));
            if ($bh_id > yii::$app->params['invalid_primary_id'])
            {
                $data = BadHistory::getBadHistoryByID($bh_id);
            }
        }

        echo json_encode($data);
        exit;
    }

    private function _addBadHistory()
    {
        $data = [
            'bh_happen_date' => trim(yii::$app->request->post('bad_history_date')),
            'bh_count' => intval(yii::$app->request->post('bad_history_count')),
            'bh_status' => yii::$app->params['valid_status'],
            'bh_create_time' => date('Y-m-d H:i:s'),
            'bh_update_time' => date('Y-m-d H:i:s'),
        ];
        return BadHistory::getDb()->createCommand()->insert(BadHistory::tableName(), $data)->execute();
    }

    private function _updateBadHistory()
    {
        $bh_id = intval(yii::$app->request->post('bad_history_bh_id'));
        $old_data = BadHistory::findOne($bh_id);

        $old_data->bh_happen_date = trim(yii::$app->request->post('bad_history_date'));
        $old_data->bh_count = intval(yii::$app->request->post('bad_history_count'));
        $old_data->bh_update_time = date('Y-m-d H:i:s');

        return $old_data->save();
    }
}