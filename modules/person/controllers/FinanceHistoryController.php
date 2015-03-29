<?php

namespace app\modules\person\controllers;

use yii;
use yii\web\Controller;
use app\modules\person\models\FinancePayment;
use app\modules\person\models\FinanceCategory;
use yii\filters\AccessControl;

class FinanceHistoryController extends Controller
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
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        $chart_data = [
            'period' => [],
            'payment' => [],
        ];
        $month_data = FinancePayment::getTotalPaymentHistoryGroupData();
        foreach ($month_data as $month_value)
        {
            $chart_data['period'][] = $month_value['period'];
            $chart_data['payment'][] = $month_value['payment'];
        }

        //choose last 30 days data.
        $fetch_days = 30;
        $start_date = date('Y-m-d', strtotime('- ' . $fetch_days . ' day'));
        $all_chart_data = $this->_getAllPaymentHistoryDataByDay($start_date);
        $sort_chart_data = [];
        if (count($all_chart_data['period']) != $fetch_days)
        {
            for($i = 1; $i <= $fetch_days; $i++)
            {
                $period_date = date('Y-m-d', strtotime($start_date . ' + ' . $i . ' day'));
                $sort_chart_data['period'][] = $period_date;
                if (!in_array($period_date, $all_chart_data['period']))
                {
                    $sort_chart_data['payment'][] = 0;
                }
                else
                {
                    $period_key = array_search($period_date, $all_chart_data['period']);
                    $sort_chart_data['payment'][] = $all_chart_data['payment'][$period_key];
                }
            }
            $all_chart_data = $sort_chart_data;
        }
        //choose last one year data.
        $start_date = date('Y-m-d', strtotime('- 1 year'));
        $category_data = $this->_getAllPaymentHistoryDataByCategory($start_date);

        $view_data = [
            'js_data' => [
                'chart_data' => $chart_data,
                'all_chart_data' => $all_chart_data,
                'category_chart_data' => $category_data,
            ],
        ];
        return $this->render('index', $view_data);
    }

    private function _getAllPaymentHistoryDataByDay($start_date)
    {
        $all_chart_data = [
            'period' => [],
            'payment' => [],
        ];
        $all_data = FinancePayment::getTotalPaymentHistoryDataByDay($start_date);
        foreach ($all_data as $all_value)
        {
            $all_chart_data['period'][] = $all_value['period'];
            $all_chart_data['payment'][] = $all_value['payment'];
        }

        return $all_chart_data;
    }

    private function _getAllPaymentHistoryDataByCategory($start_date)
    {
        $all_chart_data = [
            'category' => [],
            'payment' => [],
        ];
        $all_data = FinancePayment::getTotalPaymentHistoryDataByCategory($start_date);
        $categories = FinanceCategory::getAllParentCategory();
        foreach ($all_data as $all_value)
        {
            $all_chart_data['category'][] = $categories[$all_value['fc_id']];
            $all_chart_data['payment'][] = $all_value['payment'];
        }

        return $all_chart_data;
    }
}