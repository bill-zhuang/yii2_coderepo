<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Acl;
use yii\web\Response;

class BillController extends Controller
{
    public function beforeAction($action)
    {
        $moduleID = Yii::$app->module;
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
        return parent::afterAction($action, $result);
    }
}