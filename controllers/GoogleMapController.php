<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\GoogleMap;

class GoogleMapController extends Controller
{
    public $enableCsrfValidation = false;

    public function init()
    {

    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMarkLocation()
    {
        $lng_lat = array();
        if(isset($_GET['location']))
        {
            $location = yii::$app->request->get('location', '');
            if ($location !== '')
            {
                $lng_lat = GoogleMap::getLngLatByAddress($location);
            }
        }

        echo json_encode($lng_lat);
        exit;
    }

    public function actionMultipleLocation()
    {
        $lng_diff = 121.43 - 121.06;
        $lat_diff = 31.21 - 30.55;
        $lng_lat = array();
        for($i = 0; $i < 100; $i++)
        {
            $lng_lat[] = [
                'Longitude' => 120.51 + $lng_diff * lcg_value(),
                'Latitude' => 30.40 + $lat_diff * lcg_value()
            ];
        }

        return $this->render('index', json_encode($lng_lat));
        //$this->view->lng_lat = json_encode($lng_lat);
    }
}