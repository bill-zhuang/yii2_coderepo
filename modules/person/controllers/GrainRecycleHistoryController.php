<?php

namespace app\modules\person\controllers;

use yii;
use app\controllers\BillController;
use app\modules\person\models\GrainRecycleHistory;
use app\library\bill\Constant;
use app\library\bill\Util;
use app\library\bill\JsMessage;
use yii\web\Response;

class GrainRecycleHistoryController extends BillController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAjaxIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->_index();
    }

    public function actionAddGrainRecycleHistory()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $occurDate = isset($params['grain_recycle_history_happen_date']) ? trim($params['grain_recycle_history_happen_date']) : '';
            $occurCount = isset($params['grain_recycle_history_count']) ? intval($params['grain_recycle_history_count']) : 0;
            if (Util::validDate($occurDate) && $occurCount > 0) {
                try {
                    $grainRecycleHistory = new GrainRecycleHistory();
                    $grainRecycleHistory->happen_date = $occurDate;
                    $grainRecycleHistory->count = $occurCount;
                    $grainRecycleHistory->status = Constant::VALID_STATUS;
                    $grainRecycleHistory->create_time = date('Y-m-d H:i:s');
                    $grainRecycleHistory->update_time = date('Y-m-d H:i:s');
                    $affectedRows = intval($grainRecycleHistory->save());
                    $jsonArray = [
                        'data' => [
                            'code' => $affectedRows,
                            'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                    ? JsMessage::ADD_SUCCESS : JsMessage::ADD_FAIL,
                        ],
                    ];
                } catch (\Exception $e) {
                    Util::handleException($e, 'Error From addGrainRecycleHistory');
                }
            }
        }

        if (!isset($jsonArray['data'])) {
            $jsonArray = [
                'error' => Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO),
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionModifyGrainRecycleHistory()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $grhid = isset($params['grain_recycle_history_grhid'])
                ? intval($params['grain_recycle_history_grhid']) : Constant::INVALID_PRIMARY_ID;
            $occurCount = isset($params['grain_recycle_history_count']) ?  intval($params['grain_recycle_history_count']) : 0;
            $occurDate = isset($params['grain_recycle_history_happen_date']) ? trim($params['grain_recycle_history_happen_date']) : '';
            if ($grhid > Constant::INVALID_PRIMARY_ID && $occurCount > 0 && Util::validDate($occurDate)) {
                try {
                    $grainRecycleHistory = GrainRecycleHistory::findOne($grhid);
                    if ($grainRecycleHistory instanceof GrainRecycleHistory) {
                        $grainRecycleHistory->happen_date = $occurDate;
                        $grainRecycleHistory->count = $occurCount;
                        $grainRecycleHistory->update_time = date('Y-m-d H:i:s');
                        $affectedRows = intval($grainRecycleHistory->save());
                    }
                    $jsonArray = [
                        'data' => [
                            'code' => $affectedRows,
                            'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                    ? JsMessage::MODIFY_SUCCESS : JsMessage::MODIFY_FAIL,
                        ],
                    ];
                } catch (\Exception $e) {
                    Util::handleException($e, 'Error From modifyGrainRecycleHistory');
                }
            }
        }

        if (!isset($jsonArray['data'])) {
            $jsonArray = [
                'error' => Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO),
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionDeleteGrainRecycleHistory()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $grhid = isset($params['grhid']) ? intval($params['grhid']) : Constant::INVALID_PRIMARY_ID;
            try {
                $grainRecycleHistory = GrainRecycleHistory::findOne($grhid);
                if ($grainRecycleHistory instanceof GrainRecycleHistory) {
                    $grainRecycleHistory->status = Constant::INVALID_STATUS;
                    $grainRecycleHistory->update_time = date('Y-m-d H:i:s');
                    $affectedRows = intval($grainRecycleHistory->save());
                }
                $jsonArray = [
                    'data' => [
                        'code' => $affectedRows,
                        'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                            ? JsMessage::DELETE_SUCCESS : JsMessage::DELETE_FAIL,
                    ],
                ];
            } catch (\Exception $e) {
                Util::handleException($e, 'Error From deleteGrainRecycleHistory');
            }
        }

        if (!isset($jsonArray['data'])) {
            $jsonArray = [
                'error' => Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO),
            ];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionGetGrainRecycleHistory()
    {
        if (yii::$app->request->isGet) {
            $params = yii::$app->request->get('params', array());
            $grhid = (isset($params['grhid'])) ? intval($params['grhid']) : Constant::INVALID_PRIMARY_ID;
            $data = GrainRecycleHistory::getGrainRecycleHistoryByID($grhid);
            if (!empty($data)) {
                $jsonArray = [
                    'data' => $data,
                ];
            }
        }

        if (!isset($jsonArray['data'])) {
            $jsonArray = [
                'error' => Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO),
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    private function _index()
    {
        $params = yii::$app->request->get('params', array());
        list($currentPage, $pageLength, $start) = Util::getPaginationParamsFromUrlParamsArray($params);

        $conditions = [
            ['status' => Constant::VALID_STATUS],
        ];
        $orderBy = ['grhid' => SORT_DESC];
        $total = GrainRecycleHistory::getSearchCount($conditions);
        $data = GrainRecycleHistory::getSearchData($conditions, $start, $pageLength, $orderBy);

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