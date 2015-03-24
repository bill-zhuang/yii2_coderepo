<?php

namespace app\modules\person\controllers;

use yii;
use yii\web\Controller;
use app\modules\person\models\BadHistory;
use yii\filters\AccessControl;

class BadHistoryChartController extends Controller
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
        $all_chart_data = [
            'period' => [],
            'number' => [],
        ];
        $all_data = BadHistory::getTotalBadHistoryDataByDay();
        foreach ($all_data as $key => $all_value)
        {
            $all_chart_data['period'][] = $all_value['period'];
            $all_chart_data['number'][] = $all_value['number'];
            $all_chart_data['interval'][] = ($key == 0) ? 0 :
                intval((strtotime($all_value['period']) - strtotime($all_data[$key - 1]['period'])) / 86400);
        }

        $total = count($all_data);
        $current_date = date('Y-m-d');
        if ($all_chart_data['period'][$total - 1] != $current_date)
        {
            $all_chart_data['period'][] = date('Y-m-d');
            $all_chart_data['number'][] = 1;
            $all_chart_data['interval'][] =
                intval((strtotime($current_date) - strtotime($all_data[$total - 1]['period'])) / 86400);
        }

        $view_data = [
            'js_data' => $all_chart_data,
        ];
        return $this->render('index', $view_data);
    }
}