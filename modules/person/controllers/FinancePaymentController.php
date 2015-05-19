<?php

namespace app\modules\person\controllers;

use yii;
use yii\web\Controller;
use app\modules\person\models\FinancePayment;
use app\modules\person\models\FinanceCategory;
use yii\filters\AccessControl;

class FinancePaymentController extends Controller
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
                            'add-finance-payment',
                            'modify-finance-payment',
                            'delete-finance-payment',
                            'get-finance-payment',
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
                'compare_type' => '=',
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
            try
            {
                $affected_rows = $this->_addFinancePayment();
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
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
            try
            {
                $fp_id = intval(yii::$app->request->post('finance_payment_fp_id'));
                $finance_payment = FinancePayment::findOne($fp_id);
                if ($finance_payment instanceof FinancePayment)
                {
                    $finance_payment->fp_payment = yii::$app->request->post('finance_payment_payment');
                    $finance_payment->fp_payment_date = trim(yii::$app->request->post('finance_payment_payment_date'));
                    $finance_payment->fc_id = intval(yii::$app->request->post('finance_payment_fc_id'));
                    $finance_payment->fp_detail = trim(yii::$app->request->post('finance_payment_intro'));
                    $finance_payment->fp_update_time = date('Y-m-d H:i:s');
                    $affected_rows = intval($finance_payment->save());
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

    public function actionDeleteFinancePayment()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['fp_id']))
        {
            try
            {
                $fp_id = intval(yii::$app->request->post('fp_id'));
                $finance_payment = FinancePayment::findOne($fp_id);
                if ($finance_payment instanceof FinancePayment)
                {
                    $finance_payment->fp_status = yii::$app->params['invalid_status'];
                    $finance_payment->fp_update_time = date('Y-m-d H:i:s');
                    $affected_rows = intval($finance_payment->save());
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
}