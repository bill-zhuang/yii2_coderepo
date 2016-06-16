<?php

namespace app\modules\person\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\person\models\EjectHistory;
use app\library\bill\Constant;
use app\library\bill\Util;
use app\library\bill\JsMessage;
use yii\web\Response;

class EjectHistoryController extends Controller
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
                                'add-eject-history',
                                'modify-eject-history',
                                'delete-eject-history',
                                'get-eject-history',
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

    public function actionAddEjectHistory()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $happenDate = isset($params['eject_history_happen_date']) ? $params['eject_history_happen_date'] : '';
            $count = isset($params['eject_history_count']) ? intval($params['eject_history_count']) : 0;
            $type = isset($params['eject_history_type']) ? intval($params['eject_history_type']) : Constant::EJECT_TYPE_DREAM;
            if (Util::validDate($happenDate) && $count > 0) {
                if (!EjectHistory::isHistoryExistByHappenDateTypeEhid($happenDate, $type)) {
                    $transaction = EjectHistory::getDb()->beginTransaction();
                    try {
                        $ejectHistory = new EjectHistory();
                        $ejectHistory->happen_date = $happenDate;
                        $ejectHistory->count = $count;
                        $ejectHistory->type = $type;
                        $ejectHistory->status = Constant::VALID_STATUS;
                        $ejectHistory->create_time = date('Y-m-d H:i:s');
                        $ejectHistory->update_time = date('Y-m-d H:i:s');
                        $affectedRows = intval($ejectHistory->save());
                        $transaction->commit();
                        $jsonArray = [
                            'data' => [
                                'code' => $affectedRows,
                                'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                ? JsMessage::ADD_SUCCESS : JsMessage::ADD_FAIL,
                            ],
                        ];
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Util::handleException($e, 'Error From addEjectHistory');
                    }
                } else {
                    $jsonArray = [
                        'data' => [
                            'code' => Constant::INIT_AFFECTED_ROWS,
                            'message' => JsMessage::ADD_FAIL,
                        ]
                    ];
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

    public function actionModifyEjectHistory()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $ehid = isset($params['eject_history_ehid']) ? intval($params['eject_history_ehid']) : Constant::INVALID_PRIMARY_ID;
            $happenDate = isset($params['eject_history_happen_date']) ? $params['eject_history_happen_date'] : '';
            $count = isset($params['eject_history_count']) ? intval($params['eject_history_count']) : 0;
            $type = isset($params['eject_history_type']) ? intval($params['eject_history_type']) : Constant::EJECT_TYPE_DREAM;
            $transaction = EjectHistory::getDb()->beginTransaction();
            if (!EjectHistory::isHistoryExistByHappenDateTypeEhid($happenDate, $type, $ehid)) {
                try {
                    $ejectHistory = EjectHistory::findOne($ehid);
                    if ($ejectHistory instanceof EjectHistory) {
                        $ejectHistory->happen_date = date('Y-m-d');
                        $ejectHistory->count = $count;
                        $ejectHistory->type = $type;
                        $ejectHistory->update_time = date('Y-m-d H:i:s');
                        $affectedRows = intval($ejectHistory->save());
                    }
                    $transaction->commit();
                    $jsonArray = [
                        'data' => [
                            'code' => $affectedRows,
                            'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                ? JsMessage::MODIFY_SUCCESS : JsMessage::MODIFY_FAIL,
                        ],
                    ];
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Util::handleException($e, 'Error From modifyEjectHistory');
                }
            } else {
                $jsonArray = [
                    'data' => [
                        'code' => Constant::INIT_AFFECTED_ROWS,
                        'message' => JsMessage::MODIFY_FAIL,
                    ]
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

    public function actionDeleteEjectHistory()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $ehid = isset($params['ehid']) ? intval($params['ehid']) : Constant::INVALID_PRIMARY_ID;
            $transaction = EjectHistory::getDb()->beginTransaction();
            try {
                $ejectHistory = EjectHistory::findOne($ehid);
                if ($ejectHistory instanceof EjectHistory) {
                    $ejectHistory->status = Constant::INVALID_STATUS;
                    $ejectHistory->update_time = date('Y-m-d H:i:s');
                    $affectedRows = intval($ejectHistory->save());
                }
                $transaction->commit();
                $jsonArray = [
                    'data' => [
                        'code' => $affectedRows,
                        'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                            ? JsMessage::DELETE_SUCCESS : JsMessage::DELETE_FAIL,
                    ],
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                Util::handleException($e, 'Error From deleteEjectHistory');
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

    public function actionGetEjectHistory()
    {
        if (yii::$app->request->isGet) {
            $params = yii::$app->request->get('params', array());
            $ehid = (isset($params['ehid'])) ? intval($params['ehid']) : Constant::INVALID_PRIMARY_ID;
            $data = EjectHistory::getEjectHistoryByID($ehid);
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
        $types = [
            1 => 'Dream',
            2 => 'Bad',
        ];
        $params = yii::$app->request->get('params', array());
        list($currentPage, $pageLength, $start) = Util::getPaginationParamsFromUrlParamsArray($params);
        $tabType = isset($params['tab_type']) ? intval($params['tab_type']) : 0;

        $conditions = [
            ['status' => Constant::VALID_STATUS],
        ];
        if ($tabType !== 0) {
            $conditions[] = ['type' => $tabType];
        }
        $orderBy = ['happen_date' => SORT_DESC];
        $total = EjectHistory::getEjectHistoryCount($conditions);
        $data = EjectHistory::getEjectHistoryData($conditions, $start, $pageLength, $orderBy);
        foreach ($data as &$value) {
            $value['type'] = isset($types[$value['type']]) ? $types[$value['type']] : 'Unknown';
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