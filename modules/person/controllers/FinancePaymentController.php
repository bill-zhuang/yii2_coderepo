<?php

namespace app\modules\person\controllers;

use yii;
use yii\web\Controller;
use app\modules\person\models\FinancePayment;
use app\modules\person\models\FinanceCategory;

class FinancePaymentController extends Controller
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
        $payment_date = trim(yii::$app->request->get('payment_date', ''));

        $conditions = [
            'fp_status' => [
                'compare_type' => '=',
                'value' => yii::$app->params['valid_status']
            ]
        ];
        if ('' != $payment_date)
        {
            $conditions['fp_payment_date'] = [
                'compare_type' => '= ?',
                'value' => $payment_date
            ];
        }
        $order_by = ['fp_payment_date' => SORT_DESC];
        $total = FinancePayment::getFinancePaymentCount($conditions);
        $data = FinancePayment::getFinancePaymentData($conditions, $page_length, $start, $order_by);
        foreach ($data as $key => $value)
        {
            $data[$key]['category'] = FinanceCategory::getFinaceCategoryName($value['fc_id']);
        }

        $js_data = [
            'current_page' => $current_page,
            'page_length' => $page_length,
            'total_pages' => ceil($total / $page_length) ? ceil($total / $page_length) : yii::$app->params['init_total_page'],
            'total' => $total,
            'start' => $start,
            'payment_date' => $payment_date,
        ];
        $view_data = [
            'data' => $data,
            'parent_categories' => FinanceCategory::getAllParentCategory(),
            'js_data' => $js_data,
        ];
        return $this->render('index', $view_data);
    }
    public function actionAddFinancePayment()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['finance_payment_payment']))
        {
            $transaction = FinancePayment::getDb()->beginTransaction();
            try
            {
                $affected_rows = $this->_addFinancePayment();
                $transaction->commit();
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
                $transaction->rollBack();
                echo $e->getMessage();
            }
        }

        echo json_encode($affected_rows);
        exit;
    }

    public function actionModifyFinancePayment()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['finance_payment_fp_id']))
        {
            $transaction = FinancePayment::getDb()->beginTransaction();
            try
            {
                $affected_rows = $this->_updateFinancePayment();
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

    public function actionDeleteFinancePayment()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['fp_id']))
        {
            $transaction = FinancePayment::getDb()->beginTransaction();
            try
            {
                $fp_id = intval(yii::$app->request->post('fp_id'));
                $update_data = [
                    'fp_status' => yii::$app->params['invalid_status'],
                    'fp_update_time' => date('Y-m-d H:i:s'),
                ];
                $where = [
                    'fp_id' => $fp_id,
                    'fp_status' => yii::$app->params['valid_status'],
                ];
                $affected_rows = FinancePayment::updateAll($update_data, $where);
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

    public function actionGetFinancePayment()
    {
        $data = [];
        if (isset($_GET['fp_id']))
        {
            $fp_id = intval(yii::$app->request->get('fp_id'));
            if ($fp_id > yii::$app->params['invalid_primary_id'])
            {
                $data = FinancePayment::getFinancePaymentByID($fp_id);
            }
        }

        echo json_encode($data);
        exit;
    }

    private function _addFinancePayment()
    {
        $payments = array_filter(explode(',', yii::$app->request->post('finance_payment_payment')));
        $data = [
            'fp_payment_date' => trim(yii::$app->request->post('finance_payment_payment_date')),
            'fc_id' => intval(yii::$app->request->post('finance_payment_fc_id')),
            'fp_detail' => trim(yii::$app->request->post('finance_payment_intro')),
            'fp_status' => yii::$app->params['valid_status'],
            'fp_create_time' => date('Y-m-d H:i:s'),
            'fp_update_time' => date('Y-m-d H:i:s'),
        ];
        $affected_rows = 0;
        foreach ($payments as $payment)
        {
            $payment = floatval($payment);
            if ($payment > 0)
            {
                $data['fp_payment'] = $payment;
                $affected_rows += FinancePayment::getDb()->createCommand()->insert(FinancePayment::tableName(), $data)->execute();
            }
        }
        return $affected_rows;
    }

    private function _updateFinancePayment()
    {
        $fp_id = intval(yii::$app->request->post('finance_payment_fp_id'));
        $old_data = FinancePayment::findOne($fp_id);

        $old_data->fp_payment = yii::$app->request->post('finance_payment_payment');
        $old_data->fp_payment_date = trim(yii::$app->request->post('finance_payment_payment_date'));
        $old_data->fc_id = intval(yii::$app->request->post('finance_payment_fc_id'));
        $old_data->fp_detail = trim(yii::$app->request->post('finance_payment_intro'));
        $old_data->fp_update_time = date('Y-m-d H:i:s');

        return $old_data->save();
    }
}