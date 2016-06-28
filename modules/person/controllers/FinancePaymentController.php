<?php

namespace app\modules\person\controllers;

use yii;
use app\controllers\BillController;
use app\modules\person\models\FinancePayment;
use app\modules\person\models\FinanceCategory;
use app\modules\person\models\FinancePaymentMap;
use app\library\bill\Constant;
use app\library\bill\Util;
use app\library\bill\JsMessage;
use yii\web\Response;

class FinancePaymentController extends BillController
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

    public function actionAddFinancePayment()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $transaction = FinancePayment::getDb()->beginTransaction();
            try {
                $affectedRows = Constant::INIT_AFFECTED_ROWS;
                $params = yii::$app->request->post('params', array());
                $payments = isset($params['finance_payment_payment']) ? array_filter(explode(',', $params['finance_payment_payment'])) : [];
                $paymentDate = isset($params['finance_payment_payment_date']) ? trim($params['finance_payment_payment_date']) : '';
                $categoryIds = isset($params['finance_payment_fcid']) ? $params['finance_payment_fcid'] : [];
                $intro = isset($params['finance_payment_intro']) ? trim($params['finance_payment_intro']) : '';
                $addTime = date('Y-m-d H:i:s');
                $data = [
                    'payment_date' => $paymentDate,
                    'detail' => $intro,
                    'status' => Constant::VALID_STATUS,
                    'create_time' => $addTime,
                    'update_time' => $addTime
                ];
                if (Util::validDate($paymentDate)) {
                    foreach ($payments as $payment) {
                        $payment = floatval($payment);
                        if ($payment > 0) {
                            $data['payment'] = $payment;
                            $paymentObj = new FinancePayment();
                            foreach ($data as $tableKey => $tableValue) {
                                $paymentObj->$tableKey = $tableValue;
                            }
                            $paymentObj->save();
                            $fpId= intval(FinancePayment::getDb()->getLastInsertID());
                            $this->_addFinancePaymentMap($fpId, $categoryIds);
                            $affectedRows += $fpId;
                        }
                    }
                }
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
                Util::handleException($e, 'Error From addFinanceCategory');
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

    public function actionModifyFinancePayment()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $transaction = FinancePayment::getDb()->beginTransaction();
            try {
                $affectedRows = Constant::INIT_AFFECTED_ROWS;
                $params = yii::$app->request->post('params', array());
                $fpid = isset($params['finance_payment_fpid']) ? intval($params['finance_payment_fpid']) : Constant::INVALID_PRIMARY_ID;
                $payment = isset($params['finance_payment_payment']) ? floatval($params['finance_payment_payment']) : 0;
                $paymentDate = isset($params['finance_payment_payment_date']) ? trim($params['finance_payment_payment_date']) : '';
                $categoryIds = isset($params['finance_payment_fcid']) ? $params['finance_payment_fcid'] : [];
                $intro = isset($params['finance_payment_intro']) ? trim($params['finance_payment_intro']) : '';

                if (Util::validDate($paymentDate) && $payment > 0) {
                    $data = [
                        'fpid' => $fpid,
                        'payment' => $payment,
                        'payment_date' => $paymentDate,
                        'detail' => $intro,
                        'update_time' => date('Y-m-d H:i:s')
                    ];
                    $paymentObj = FinancePayment::findOne($fpid);
                    if ($paymentObj instanceof FinancePayment) {
                        foreach ($data as $tableKey => $tableValue) {
                            $paymentObj->$tableKey = $tableValue;
                        }
                        $affectedRows = intval($paymentObj->save());
                        $affectedRows += $this->_updateFinancePaymentMap($fpid, $categoryIds);
                    }
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
                Util::handleException($e, 'Error From modifyFinanceCategory');
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

    public function actionDeleteFinancePayment()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $transaction = FinancePayment::getDb()->beginTransaction();
            try {
                $affectedRows = Constant::INIT_AFFECTED_ROWS;
                $params = yii::$app->request->post('params', array());
                $fpid = isset($params['fpid']) ? intval($params['fpid']) : Constant::INVALID_PRIMARY_ID;
                if ($fpid > Constant::INVALID_PRIMARY_ID) {
                    $paymentObj = FinancePayment::findOne([
                        'fpid' => $fpid,
                        'status' => Constant::VALID_STATUS
                    ]);
                    if ($paymentObj instanceof FinancePayment) {
                        $paymentObj->status = Constant::INVALID_STATUS;
                        $paymentObj->update_time = date('Y-m-d H:i:s');
                        $affectedRows = intval($paymentObj->save());
                    }
                    //update map table
                    $mapObjs = FinancePaymentMap::findAll([
                        'fpid' => $fpid,
                        'status' => Constant::VALID_STATUS
                    ]);
                    foreach ($mapObjs as $mapObj) {
                        if ($mapObj instanceof FinancePaymentMap) {
                            $mapObj->status = Constant::INVALID_STATUS;
                            $mapObj->update_time = date('Y-m-d H:i:s');
                            $affectedRows += intval($mapObj->save());
                        }
                    }
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
                Util::handleException($e, 'Error From deleteFinancePayment');
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

    public function actionGetFinancePayment()
    {
        if (yii::$app->request->isGet) {
            $params = yii::$app->request->get('params', array());
            $fpid = (isset($params['fpid'])) ? intval($params['fpid']) : Constant::INVALID_PRIMARY_ID;
            $data = FinancePayment::getFinancePaymentByID($fpid);
            $data['fcids'] = FinancePaymentMap::getFinanceCategoryIDs($fpid);
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
        $paymentDate = isset($params['payment_date']) ? trim($params['payment_date']) : '';
        $financeCategoryId = isset($params['category_parent_id']) ? intval($params['category_parent_id']) : 0;

        $conditions = [
            ['status' => Constant::VALID_STATUS],
        ];
        if ('' != $paymentDate) {
            $conditions[] = ['payment_date' => $paymentDate];
        }
        if (0 !== $financeCategoryId) {
            $fpids = FinancePaymentMap::getFpidByFcid($financeCategoryId, ['create_time' => SORT_DESC], $start, $pageLength);
            if (!empty($fpids)) {
                $conditions[] = ['in', 'fpid', $fpids];
            } else {
                $conditions[] = [1 => 0];
            }
        }
        $orderBy = ['payment_date' => SORT_DESC];
        $total = FinancePayment::getFinancePaymentCount($conditions);
        $data = FinancePayment::getFinancePaymentData($conditions, $start, $pageLength, $orderBy);
        foreach ($data as &$value) {
            $fcids = FinancePaymentMap::getFinanceCategoryIDs($value['fpid']);
            if (!empty($fcids)) {
                $value['category'] =
                    implode(',', FinanceCategory::getFinanceCategoryNames($fcids));
            } else {
                $value['category'] = '';
            }
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

    private function _addFinancePaymentMap($fpid, array $fcids)
    {
        $addTime = date('Y-m-d H:i:s');
        $mapData = [
            'fpid' => 0,
            'fcid' => 0,
            'status' => Constant::VALID_STATUS,
            'create_time' => $addTime,
            'update_time' => $addTime
        ];

        $mapData['fpid'] = $fpid;
        foreach ($fcids as $categoryId) {
            $mapData['fcid'] = $categoryId;
            FinancePaymentMap::getDb()->createCommand()->insert(FinancePaymentMap::tableName(), $mapData)->execute();
        }
    }

    private function _updateFinancePaymentMap($fpid, array $fcids)
    {
        $affectedRows = Constant::INIT_AFFECTED_ROWS;
        $insertData = [
            'fpid' => $fpid,
            'fcid' => 0,
            'status' => Constant::VALID_STATUS,
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s')
        ];
        $originFcids = FinancePaymentMap::getFinanceCategoryIDs($fpid);
        $updateFcids = array_diff($originFcids, $fcids);
        $insertFcids = array_diff($fcids, $originFcids);
        foreach ($updateFcids as $fcid) {
            $mapObj = FinancePaymentMap::findOne(
                [
                    'fpid' => $fpid,
                    'fcid' => $fcid,
                    'status' => Constant::VALID_STATUS
                ]
            );
            if ($mapObj instanceof FinancePaymentMap)
            {
                $mapObj->status = yii::$app->params['invalid_status'];
                $mapObj->update_time = date('Y-m-d H:i:s');
                $affectedRows += intval($mapObj->save());
            }
        }

        foreach ($insertFcids as $fcid) {
            $insertData['fcid'] = $fcid;
            $affectedRows += FinancePaymentMap::getDb()->createCommand()->insert(FinancePaymentMap::tableName(), $insertData)->execute();
        }

        return $affectedRows;
    }
}