<?php

namespace app\controllers;

use app\models\BackendRoleAcl;
use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\BackendAcl;
use app\library\bill\Constant;
use app\library\bill\Util;
use app\library\bill\JsMessage;
use yii\web\Response;

class BackendAclController extends Controller
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
                                'load-backend-acl',
                                'modify-backend-acl',
                                'delete-backend-acl',
                                'get-backend-acl',
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

    public function actionLoadBackendAcl()
    {
        $jsonArray = [];

        if (!isset($jsonArray['data'])) {
            $jsonArray = [
                'error' => Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO),
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionModifyBackendAcl()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $baid = intval($params['backend_acl_baid']);
            try {
                $backendAcl = BackendAcl::findOne($baid);
                if ($backendAcl instanceof BackendAcl) {
                    $backendAcl->name = trim($params['backend_acl_name']);
                    $backendAcl->update_time = date('Y-m-d H:i:s');
                    $affectedRows = intval($backendAcl->save());
                }
                $jsonArray = [
                    'data' => [
                        'code' => $affectedRows,
                        'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                            ? JsMessage::MODIFY_SUCCESS : JsMessage::MODIFY_FAIL,
                    ],
                ];
            } catch (\Exception $e) {
                Util::handleException($e, 'Error From modifyBackendAcl');
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

    public function actionDeleteBackendAcl()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $params = yii::$app->request->post('params', array());
            $baid = isset($params['baid']) ? intval($params['baid']) : Constant::INVALID_PRIMARY_ID;
            $transaction = BackendAcl::getDb()->beginTransaction();
            try {
                $where = [
                    ['baid' => $baid],
                    ['status' => Constant::VALID_STATUS],
                ];
                $affectedRows = BackendAcl::deleteAll($where);
                BackendRoleAcl::deleteAll($where);
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
                Util::handleException($e, 'Error From deleteBackendAcl');
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

    public function actionGetBackendAcl()
    {
        if (yii::$app->request->isGet) {
            $params = yii::$app->request->get('params', array());
            $baid = (isset($params['baid'])) ? intval($params['baid']) : Constant::INVALID_PRIMARY_ID;
            $data = BackendAcl::getBackendAclByID($baid);
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
        $keyword = isset($params['keyword']) ? trim($params['keyword']) : '';

        $conditions = [
            ['status' => Constant::VALID_STATUS],
        ];
        if ($keyword !== '') {
            $conditions[] = ['or',
                ['like', 'module', Util::getLikeString($keyword), false],
                ['like', 'controller', Util::getLikeString($keyword), false],
                ['like', 'action', Util::getLikeString($keyword), false],
            ];
        }
        $orderBy = null;
        $groupBy = ['module', 'controller', 'action'];
        $total = BackendAcl::getBackendAclCount($conditions);
        $data = BackendAcl::getBackendAclData($conditions, $start, $pageLength, $orderBy, $groupBy);

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