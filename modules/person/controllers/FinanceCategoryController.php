<?php

namespace app\modules\person\controllers;

use app\modules\person\models\FinancePaymentMap;
use yii;
use app\controllers\BillController;
use app\modules\person\models\FinanceCategory;
use app\library\bill\Constant;
use app\library\bill\Util;
use app\library\bill\JsMessage;
use yii\web\Response;

class FinanceCategoryController extends BillController
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

    public function actionAddFinanceCategory()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            try {
                $params = yii::$app->request->post('params', array());
                $name = isset($params['finance_category_name']) ? trim($params['finance_category_name']) : '';
                $parentId = isset($params['finance_category_parent_id']) ? intval($params['finance_category_parent_id']) : 0;
                $weight = isset($params['finance_category_weight'])
                    ? intval($params['finance_category_weight']) : Constant::DEFAULT_WEIGHT;
                $addTime = date('Y-m-d H:i:s');

                if (!FinanceCategory::isFinanceCategoryExist($name, 0)) {
                    $financeCategory = new FinanceCategory();
                    $financeCategory->name = $name;
                    $financeCategory->parent_id = $parentId;
                    $financeCategory->weight = $weight;
                    $financeCategory->status = Constant::VALID_STATUS;
                    $financeCategory->create_time = $addTime;
                    $financeCategory->update_time = $addTime;
                    $affectedRows = intval($financeCategory->save());
                    $jsonArray = [
                        'data' => [
                            'code' => $affectedRows,
                            'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                    ? JsMessage::ADD_SUCCESS : JsMessage::ADD_FAIL,
                        ],
                    ];
                }
            } catch (\Exception $e) {
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

    public function actionModifyFinanceCategory()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            try {
                $params = yii::$app->request->post('params', array());
                $fcid = isset($params['finance_category_fcid']) ? intval($params['finance_category_fcid']) : 0;
                $name = isset($params['finance_category_name']) ? trim($params['finance_category_name']) : '';
                $parentId = isset($params['finance_category_parent_id']) ? intval($params['finance_category_parent_id']) : 0;
                $weight = isset($params['finance_category_weight'])
                    ? intval($params['finance_category_weight']) : Constant::DEFAULT_WEIGHT;

                if (!FinanceCategory::isFinanceCategoryExist($name, $fcid)) {
                    $financeCategory = FinanceCategory::findOne($fcid);
                    if ($financeCategory instanceof FinanceCategory)
                    {
                        $financeCategory->name = $name;
                        $financeCategory->parent_id = $parentId;
                        $financeCategory->weight = $weight;
                        $financeCategory->update_time = date('Y-m-d H:i:s');
                        $affectedRows = intval($financeCategory->save());
                        $jsonArray = [
                            'data' => [
                                'code' => $affectedRows,
                                'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                        ? JsMessage::MODIFY_SUCCESS : JsMessage::MODIFY_FAIL,
                            ],
                        ];
                    }
                }
            } catch (\Exception $e) {
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

    public function actionDeleteFinanceCategory()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            try {
                $params = yii::$app->request->post('params', array());
                $fcid = isset($params['fcid']) ? intval($params['fcid']) : Constant::INVALID_PRIMARY_ID;
                if (!FinancePaymentMap::isPaymentExistUnderFcid($fcid)) {
                    $updateData = [
                        'status' => Constant::INVALID_STATUS,
                        'update_time' => date('Y-m-d H:i:s')
                    ];
                    $where = [
                        'and', 'status=' . Constant::VALID_STATUS, ['or', 'fcid=' . $fcid, 'parent_id=' . $fcid],
                    ];
                    $affectedRows = FinanceCategory::updateAll($updateData, $where);
                    $jsonArray = [
                        'data' => [
                            'code' => $affectedRows,
                            'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                    ? JsMessage::DELETE_SUCCESS : JsMessage::DELETE_FAIL,
                        ]
                    ];
                } else {
                    $jsonArray = [
                        'data' => [
                            'code' => 0,
                            'message' => JsMessage::PAYMENT_EXIST_UNDER_CATEGORY,
                        ]
                    ];
                }
            } catch (\Exception $e) {
                Util::handleException($e, 'Error From deleteFinanceCategory');
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

    public function actionGetFinanceCategory()
    {
        if (yii::$app->request->isGet) {
            $params = yii::$app->request->get('params', array());
            $fcid = (isset($params['fcid'])) ? intval($params['fcid']) : Constant::INVALID_PRIMARY_ID;
            $data = FinanceCategory::getFinanceCategoryByID($fcid);
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

    public function actionGetFinanceSubcategory()
    {
        $jsonArray = [];
        if (yii::$app->request->isGet) {
            $params = yii::$app->request->get('params', array());
            $parentId = isset($params['parent_id']) ? intval($params['parent_id']) : Constant::INVALID_PRIMARY_ID;
            $subcategoryData = FinanceCategory::getFinanceSubcategory($parentId);
            if (!empty($data)) {
                $jsonArray = [
                    'data' => [
                        'currentItemCount' => count($subcategoryData),
                        'items' => $subcategoryData,
                    ],
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

    public function actionGetFinanceMainCategory()
    {
        $data = FinanceCategory::getAllParentCategory();
        $jsonData = [
            'data' => [
                'currentItemCount' => count($data),
                'items' => $data,
            ],
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonData;
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
            $conditions[] = ['like', 'name', Util::getLikeString($keyword), false];
        }
        $orderBy = ['weight' => SORT_DESC];
        $total = FinanceCategory::getFinanceCategoryCount($conditions);
        $data = FinanceCategory::getFinanceCategoryData($conditions, $start, $pageLength, $orderBy);
        foreach ($data as &$value) {
            $value['parent'] = $value['parent_id'] == 0 ?
                '无' : FinanceCategory::getParentCategoryName($value['parent_id']);
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