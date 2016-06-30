<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Acl;
use yii\web\Response;
use app\library\bill\Constant;
use app\library\bill\Util;

class BillController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->controller->module->module != null) {
            $moduleID = Yii::$app->controller->module->id;
        } else {
            $moduleID = '';
        }
        $controllerID = Yii::$app->controller->id;
        $actionID = $this->action->id;
        $isAjax = Yii::$app->request->isAjax;
        $result = Acl::isAccessGranted($moduleID, $controllerID, $actionID, $isAjax);
        if ($result === true) {
            return parent::beforeAction($action);
        } else if (is_array($result)){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        } else {
            return $this->redirect($result);
        }
    }

    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        //trigger sql query info
        $sqlInfo = $this->_dbProfiling();
        if (!Util::isProductionEnv() && yii::$app->request->get('sql', 0) == 1) {
            echo '<pre>';
            print_r($sqlInfo);
            exit;
        }
        if (isset($sqlInfo['queryCost']) && $sqlInfo['queryCost'] > Constant::SQL_QUERY_COST_TRIGGER) {
            //TODO slow query trigger
        }
        return $result;
    }

    //update from Yii::getLogger()->getDbProfiling()
    private function _dbProfiling()
    {
        $sqlInfo = [
            'queryCount' => 0,
            'queryCost' => 0,
            'queries' => [],
        ];
        $timings = Yii::getLogger()->getProfiling(['yii\db\Command::query', 'yii\db\Command::execute']);
        $sqlInfo['queryCount'] = count($timings);
        foreach ($timings as $timing) {
            $sqlInfo['queryCost'] += $timing['duration'];
            $sqlInfo['queries'][] = [
                'sql' => $timing['info'],
                'queryCost' => $timing['duration'],
            ];
        }

        return $sqlInfo;
    }
}