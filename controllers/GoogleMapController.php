<?php

namespace app\controllers;

use yii;
use app\library\bill\GoogleMap;
use app\library\bill\Util;
use yii\web\Response;

class GoogleMapController extends BillController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMarkLocation()
    {
        $jsonArray = [];
        $params = yii::$app->request->get('params', array());
        if(isset($params['location'])) {
            $coordinateInfo = GoogleMap::getLngLatByAddress($params['location']);
            if (!empty($coordinateInfo)) {
                $jsonArray['data'] = $coordinateInfo;
            } else {
                $jsonArray['error'] = Util::getJsonResponseErrorArray('200', 'Fetch Location coordinate failed.');
            }
        } else {
            $jsonArray['error'] = Util::getJsonResponseErrorArray('200', 'Param location not provided.');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionMultipleLocation()
    {
        return $this->render('multiple-location');
    }

    public function actionAjaxMultipleLocation()
    {
        $jsonArray = [
            'data' => [
                'coordinates' => $this->_multipleLocation()
            ],
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    private function _multipleLocation()
    {
        $lngDiff = 121.43 - 121.06;
        $latDiff = 31.21 - 30.55;
        $lngLat = array();
        for($i = 0; $i < 100; $i++) {
            $lngLat[] = array('Longitude' => 120.51 + $lngDiff * lcg_value(), 'Latitude' => 30.40 + $latDiff * lcg_value());
        }

        return $lngLat;
    }
}