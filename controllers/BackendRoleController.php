<?php

namespace app\controllers;

use app\models\BackendAcl;
use app\models\BackendRoleAcl;
use app\models\BackendUser;
use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\BackendRole;
use app\library\bill\Constant;
use app\library\bill\Util;
use app\library\bill\JsMessage;
use yii\web\Response;

class BackendRoleController extends Controller
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
                                'add-backend-role',
                                'modify-backend-role',
                                'delete-backend-role',
                                'get-backend-role',
                                'get-backend-role-acl',
                                'modify-backend-role-acl',
                                'get-all-roles',
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

    public function actionAddBackendRole()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $roleName = trim($params['backend_role_role']);
            if ($roleName !== '' && !BackendRole::isRoleExist($roleName, Constant::INVALID_PRIMARY_ID)) {
                try {
                    $backendRole = new BackendRole();
                    $backendRole->role = $roleName;
                    $backendRole->status = Constant::VALID_STATUS;
                    $backendRole->create_time = date('Y-m-d H:i:s');
                    $backendRole->update_time = date('Y-m-d H:i:s');
                    $affectedRows = intval($backendRole->save());
                    $jsonArray = [
                        'data' => [
                            'code' => $affectedRows,
                            'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                    ? JsMessage::ADD_SUCCESS : JsMessage::ADD_FAIL,
                        ],
                    ];
                } catch (\Exception $e) {
                    Util::handleException($e, 'Error From addBackendRole');
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

    public function actionModifyBackendRole()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $brid = intval($params['backend_role_brid']);
            $roleName = trim($params['backend_role_role']);
            if ($brid > Constant::INVALID_PRIMARY_ID && $roleName !== '' && !BackendRole::isRoleExist($roleName, $brid)) {
                try {
                    $backendRole = BackendRole::findOne($brid);
                    if ($backendRole instanceof BackendRole) {
                        $backendRole->role = $roleName;
                        $backendRole->update_time = date('Y-m-d H:i:s');
                        $affectedRows = intval($backendRole->save());
                    }
                    $jsonArray = [
                        'data' => [
                            'code' => $affectedRows,
                            'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                    ? JsMessage::MODIFY_SUCCESS : JsMessage::MODIFY_FAIL,
                        ],
                    ];
                } catch (\Exception $e) {
                    Util::handleException($e, 'Error From modifyBackendRole');
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

    public function actionDeleteBackendRole()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $brid = isset($params['brid']) ? intval($params['brid']) : Constant::INVALID_PRIMARY_ID;
            if ($brid > Constant::INVALID_PRIMARY_ID) {
                if (BackendUser::getRoleCount($brid) == 0) {
                    try {
                        $backendRole = BackendRole::findOne($brid);
                        if ($backendRole instanceof BackendRole) {
                            $backendRole->status = Constant::INVALID_STATUS;
                            $backendRole->update_time = date('Y-m-d H:i:s');
                            $affectedRows = intval($backendRole->save());
                        }
                        $jsonArray = [
                            'data' => [
                                'code' => $affectedRows,
                                'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                        ? JsMessage::DELETE_SUCCESS : JsMessage::DELETE_FAIL,
                            ],
                        ];
                    } catch (\Exception $e) {
                        Util::handleException($e, 'Error From deleteBackendRole');
                    }
                } else {
                    $jsonArray = [
                        'error' => [
                            'message' => '改角色下还有用户，删除失败',
                        ],
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

    public function actionGetBackendRole()
    {
        if (yii::$app->request->isGet) {
            $params = yii::$app->request->get('params', array());
            $brid = (isset($params['brid'])) ? intval($params['brid']) : Constant::INVALID_PRIMARY_ID;
            $data = BackendRole::getBackendRoleByID($brid);
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

    public function actionGetBackendRoleAcl()
    {
        if (yii::$app->request->isGet) {
            $params = yii::$app->request->get('params', array());
            $brid = (isset($params['brid'])) ? intval($params['brid']) : Constant::INVALID_PRIMARY_ID;
            //
            $aclList = BackendAcl::getAclList();
            $jsonArray = [
                'data' => [
                    'brid' => $brid,
                    'aclList' => $aclList,
                    'roleAcl' => BackendRoleAcl::getUserAclByBrid($brid),
                ],
            ];
        }

        if (!isset($jsonArray['data'])) {
            $jsonArray = [
                'error' => Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO),
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionModifyBackendRoleAcl()
    {
        if (yii::$app->request->isPost) {
            $params = yii::$app->request->post('params', array());
            $brid = (isset($params['backend_role_acl_brid']))
                ? intval($params['backend_role_acl_brid']) : Constant::INVALID_PRIMARY_ID;
            $submitBaids = (isset($params['backend_role_acl_baid'])) ? array_filter($params['backend_role_acl_baid']) : [];
            if ($brid > Constant::INVALID_PRIMARY_ID && !empty($submitBaids)) {
                //
                $existBaids = BackendRoleAcl::getUserAclByBrid($brid);
                $addBaids = array_diff($submitBaids, $existBaids);
                $removeBaids = array_diff($existBaids, $submitBaids);
                //transaction
                $transaction = BackendRoleAcl::getDb()->beginTransaction();
                try {
                    $affectedRows = Constant::INIT_AFFECTED_ROWS;
                    if (!empty($removeBaids)) {
                        $affectedRows += BackendRoleAcl::deleteAll([
                            ['brid' => $brid],
                            ['in', 'baid', $removeBaids],
                        ]);
                    }
                    if (!empty($addBaids)) {
                        $initData = [
                            'brid' => $brid,
                            'baid' => Constant::INVALID_PRIMARY_ID,
                            'status' => Constant::VALID_STATUS,
                            'create_time' => date('Y-m-d H:i:s'),
                            'update_time' => date('Y-m-d H:i:s'),
                        ];
                        foreach ($addBaids as $baid) {
                            $initData['baid'] = $baid;
                            $roleAcl = new BackendRoleAcl();
                            foreach ($initData as $keyRoleAcl => $valueRoleAcl) {
                                $roleAcl->$keyRoleAcl = $valueRoleAcl;
                            }
                            $affectedRows += intval($roleAcl->save());
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
                    Util::handleException($e, 'Error from modifyBackendRoleAcl');
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

    public function actionGetAllRoles()
    {
        $jsonArray = [
            'data' => BackendRole::getAllRoles(),
        ];
        echo json_encode($jsonArray);
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
            $conditions[] = ['like', 'role', Util::getLikeString($keyword)];
        }
        $orderBy = ['brid' => SORT_DESC];
        $total = BackendRole::getBackendRoleCount($conditions);
        $data = BackendRole::getBackendRoleData($conditions, $start, $pageLength, $orderBy);
        foreach ($data as &$value) {
            $value['count'] = BackendUser::getRoleCount($value['brid']);
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