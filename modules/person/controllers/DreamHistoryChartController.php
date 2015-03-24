<?php

namespace app\modules\person\controllers;

use yii;
use yii\web\Controller;
use app\modules\person\models\DreamHistory;
use yii\filters\AccessControl;

class DreamHistoryChartController extends Controller
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
            'number' => [],
        ];
        $month_data = DreamHistory::getTotalDreamHistoryGroupData();
        foreach ($month_data as $month_value)
        {
            $chart_data['period'][] = $month_value['period'];
            $chart_data['number'][] = $month_value['number'];
        }

        $all_chart_data = $this->_getAllDreamHistoryDataByDay();
        $view_data = [
            'js_data' => [
                'chart_data' => json_encode($chart_data),
                'all_chart_data' => json_encode($all_chart_data),
            ],
        ];
        return $this->render('index', $view_data);
    }

    private function _getAllDreamHistoryDataByDay()
    {
        $all_chart_data = [
            'period' => [],
            'number' => [],
        ];
        $all_data = DreamHistory::getTotalDreamHistoryDataByDay();
        foreach ($all_data as $key => $all_value)
        {
            $all_chart_data['period'][] = $all_value['period'];
            $all_chart_data['number'][] = $all_value['number'];
            $all_chart_data['interval'][] = ($key == 0) ? 0 :
                intval((strtotime($all_value['period']) - strtotime($all_data[$key - 1]['period'])) / 86400);
        }

        return $all_chart_data;
    }
}