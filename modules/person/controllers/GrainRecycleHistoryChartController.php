<?php

namespace app\modules\person\controllers;

use yii;
use app\controllers\BillController;
use app\modules\person\models\GrainRecycleHistory;
use app\library\bill\Util;
use yii\web\Response;

class GrainRecycleHistoryChartController extends BillController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAjaxGrainRecycleHistoryPeriod()
    {
        $params = yii::$app->request->get('params', array());
        $startDate = (isset($params['day_start_date']) && Util::validDate($params['day_start_date']))
            ? trim($params['day_start_date']) : date('Y-m-d', strtotime('-1 month'));
        $endDate = (isset($params['day_end_date']) && Util::validDate($params['day_end_date']))
            ? trim($params['day_end_date']) : date('Y-m-d');
        $data = [];
        $dayInterval = intval((strtotime($endDate) - strtotime($startDate)) / 86400);
        for($i = 0; $i <= $dayInterval; $i++) {
            $periodDate = date('Y-m-d', strtotime($startDate . " + {$i} day"));
            $data[$periodDate] = 0;
        }
        $dayData = GrainRecycleHistory::getTotalGrainRecycleHistoryDataByDay($startDate, $endDate);
        foreach ($dayData as $dayValue) {
            if (isset($data[$dayValue['period']])) {
                $data[$dayValue['period']] = intval($dayValue['number']);
            }
        }
        $jsonArray = [
            'searchData' => [
                'startDate' => $startDate,
                'endDate' => $endDate,
            ],
            'data' => [
                'days' => array_keys($data),
                'data' => array_values($data),
            ],
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionAjaxGrainRecycleHistoryMonth()
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
        $monthData = GrainRecycleHistory::getTotalGrainRecycleHistoryGroupData($startDate, $endDate);
        foreach ($monthData as $monthValue) {
            $data['months'][] = $monthValue['period'];
            $tempData[$monthValue['period']] = $monthValue['number'];
        }

        $data['months'] = Util::getMonthRange($data['months']);
        foreach ($data['months'] as $month) {
            if (isset($tempData[$month])) {
                $data['data'][] = intval($tempData[$month]);
            } else {
                $data['data'][] = 0;
            }
        }
        $jsonArray = [
            'searchData' => [
                'startDate' => $startDate,
                'endDate' => $endDate,
            ],
            'data' => $data
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }
}