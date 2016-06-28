<?php

namespace app\modules\person\controllers;

use yii;
use app\controllers\BillController;
use app\modules\person\models\FinancePayment;
use app\modules\person\models\FinanceCategory;
use app\library\bill\Util;
use yii\web\Response;

class FinanceHistoryController extends BillController
{
    private $_categories;
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAjaxFinanceHistoryPeriod()
    {
        $params = yii::$app->request->get('params', array());
        $startDate = (isset($params['day_start_date']) && Util::validDate($params['day_start_date']))
            ? trim($params['day_start_date']) : date('Y-m-d', strtotime('-1 month'));
        $endDate = (isset($params['day_end_date']) && Util::validDate($params['day_end_date']))
            ? trim($params['day_end_date']) : date('Y-m-d');
        $fcid = (isset($params['day_category_id'])) ? intval($params['day_category_id']) : 0;
        $data = [];
        $dayInterval = intval((strtotime($endDate) - strtotime($startDate)) / 86400);
        for($i = 0; $i <= $dayInterval; $i++) {
            $periodDate = date('Y-m-d', strtotime($startDate . " + {$i} day"));
            $data[$periodDate] = 0.00;
        }
        $dayData = FinancePayment::getTotalPaymentHistoryDataByDay($startDate, $endDate, $fcid);
        foreach ($dayData as $dayValue) {
            if (isset($data[$dayValue['period']])) {
                $data[$dayValue['period']] = floatval($dayValue['payment']);
            }
        }
        $jsonArray = [
            'data' => [
                'days' => array_keys($data),
                'data' => array_values($data),
            ],
            'fcid' => $fcid,
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionAjaxFinanceHistoryMonth()
    {
        $params = yii::$app->request->get('params', array());
        $startDate = (isset($params['month_start_date']) && Util::validDate($params['month_start_date']))
            ? trim($params['month_start_date']) : date('Y-m', strtotime('-11 month')) . '-01';
        $endDate = (isset($params['month_end_date']) && Util::validDate($params['month_end_date']))
            ? trim($params['month_end_date']) : '';
        $data = [
            'months' => [],
            'data' => [],
        ];
        $tempData = [];
        $monthData = FinancePayment::getTotalPaymentHistoryGroupData($startDate, $endDate);
        foreach ($monthData as $monthValue) {
            $data['months'][] = $monthValue['period'];
            $tempData[$monthValue['period']] = $monthValue['payment'];
        }
        $data['months'] = Util::getMonthRange($data['months']);
        foreach ($data['months'] as $month) {
            if (isset($tempData[$month])) {
                $data['data'][] = floatval($tempData[$month]);
            } else {
                $data['data'][] = 0.00;
            }
        }
        $jsonArray = [
            'data' => $data,
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionAjaxFinanceHistoryMonthCategory()
    {
        $startDate = date('Y-m-d', strtotime('-1 month'));
        $monthData = $this->_getAllPaymentHistoryDataByCategory($startDate);
        $monthData['total'] = FinancePayment::getSumPaymentByDate($startDate);
        $jsonArray = [
            'data' => $monthData,
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionAjaxFinanceHistoryYearCategory()
    {
        $startDate = date('Y-m-d', strtotime('- 1 year'));
        $yearData = $this->_getAllPaymentHistoryDataByCategory($startDate);
        $yearData['total'] = FinancePayment::getSumPaymentByDate($startDate);
        $jsonArray = [
            'data' => $yearData,
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    private function _getAllPaymentHistoryDataByCategory($startDate)
    {
        if (empty($this->_categories)) {
            $this->_categories = FinanceCategory::getAllParentCategory(true);
        }
        $data = [
            'categories' => [],
            'data' => [],
        ];
        $yearData = FinancePayment::getTotalPaymentHistoryDataByCategory($startDate);
        foreach ($yearData as $yearValue) {
            $data['categories'][] = isset($this->_categories[$yearValue['fcid']])
                ? $this->_categories[$yearValue['fcid']] : '';
            $data['data'][] = floatval($yearValue['payment']);
        }

        return $data;
    }
}