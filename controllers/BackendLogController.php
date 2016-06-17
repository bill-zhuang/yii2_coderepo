<?php

namespace app\controllers;

use app\models\BackendUser;
use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\BackendLog;
use app\library\bill\Constant;
use app\library\bill\Util;
use yii\web\Response;

class BackendLogController extends Controller
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
                            'actions' => [
                                'index',
                                'ajax-index',
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAjaxIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->_index();
    }

    private function _index()
    {
        $params = yii::$app->request->get('params', array());
        list($currentPage, $pageLength, $start) = Util::getPaginationParamsFromUrlParamsArray($params);
        $keyword = isset($params['keyword']) ? trim($params['keyword']) : '';

        $conditions = [
            ['status' => Constant::VALID_STATUS],
        ];
        if ('' !== $keyword) {
            $conditions[] = ['like', 'content', Util::getLikeString($keyword)];
        }
        $orderBy = ['blid' => SORT_DESC];
        $total = BackendLog::getBackendLogCount($conditions);
        $data = BackendLog::getBackendLogData($conditions, $start, $pageLength, $orderBy);
        $cacheUserName = [];
        foreach ($data as &$value) {
            if (!isset($cacheUserName[$value['buid']])) {
                $cacheUserName[$value['buid']] = BackendUser::getUserName($value['buid']);
            }
            $value['name'] = $cacheUserName[$value['buid']];
        }

        $jsonData = [
            'data' => [
                'totalPages' => Util::getTotalPages($total, $pageLength),
                'pageIndex' => $currentPage,
                'totalItems' => $total,
                'startIndex' => $start + 1,
                'itemsPerPage' => $pageLength,
                'currentItemCount' => count($data),
                'items' => $data,
            ],
        ];
        return $jsonData;
    }

}