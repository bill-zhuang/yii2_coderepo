<?php

namespace app\modules\person\controllers;

use yii;
use app\controllers\BillController;
use app\modules\person\models\EjectHistory;
use app\library\bill\Constant;
use app\library\bill\Util;
use yii\web\Response;

class EjectHistoryChartController extends BillController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAjaxEjectHistoryPeriod()
    {
        $params = yii::$app->request->get('params', array());
        $startDate = (isset($params['day_start_date']) && Util::validDate($params['day_start_date']))
            ? trim($params['day_start_date']) : date('Y-m-d', strtotime('-1 year'));
        $endDate = (isset($params['day_end_date']) && Util::validDate($params['day_end_date']))
            ? trim($params['day_end_date']) : '';
        $data = [];
        $types = $this->_getEjectTypes();
        foreach ($types as $typeName => $type) {
            $ejectData = EjectHistory::getTotalEjectHistoryDataByDay($startDate, $endDate, $type);
            $typeData = [];
            $previousTimestamp = 0;
            foreach ($ejectData as $ejctKey => $ejectValue) {
                $currentTimestamp = strtotime($ejectValue['period'] . ' 08:00:00');
                $typeData[] = [
                    $currentTimestamp * 1000,
                    ($ejctKey > 0 ? (intval($currentTimestamp - $previousTimestamp) / Constant::DAY_SECONDS) : 0),
                ];
                $previousTimestamp = $currentTimestamp;
            }
            $data[] = [
                'name' => $typeName,
                'data' => $typeData,
            ];
        }

        $jsonArray = [
            'data' => $data
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionAjaxEjectHistoryMonth()
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

        $types = $this->_getEjectTypes();
        $months = [];
        $tempData = [];
        foreach ($types as $typeName => $type) {
            $typeData = [];
            $monthData = EjectHistory::getTotalEjectHistoryGroupData($startDate, $endDate, $type);
            foreach ($monthData as $monthValue) {
                $typeData[] = intval($monthValue['number']);
                if (!in_array($monthValue['period'], $months)) {
                    $months[] = $monthValue['period'];
                }
                $typeData[$monthValue['period']] = $monthValue['number'];
            }
            $tempData[$typeName] = $typeData;
        }

        $data['months'] = Util::getMonthRange($months);

        foreach ($types as $typeName => $type) {
            $typeData = [];
            foreach ($data['months'] as $month) {
                if (isset($tempData[$typeName][$month])) {
                    $typeData[] = intval($tempData[$typeName][$month]);
                } else {
                    $typeData[] = 0;
                }
            }
            $data['data'][] = [
                'name' => $typeName,
                'data' => $typeData,
            ];
        }
        $jsonArray = [
            'data' => $data
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    private function _getEjectTypes()
    {
        $types = [
            'Dream' => Constant::EJECT_TYPE_DREAM,
            'Bad' => Constant::EJECT_TYPE_BAD,
        ];
        return $types;
    }
}