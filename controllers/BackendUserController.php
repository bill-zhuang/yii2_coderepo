<?php

namespace app\controllers;

use app\models\BackendRole;
use yii;
use app\models\BackendUser;
use app\library\bill\Constant;
use app\library\bill\Util;
use app\library\bill\Security;
use app\library\bill\JsMessage;
use yii\web\Response;

class BackendUserController extends BillController
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

    public function actionAddBackendUser()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $name = trim($params['backend_user_name']);
            if (!BackendUser::isUserNameExist($name, Constant::INVALID_PRIMARY_ID)) {
                $security = new Security();
                $salt = $security->generateRandomString(Constant::SALT_STRING_LENGTH);
                try {
                    $backendUser = new BackendUser();
                    $backendUser->name = $name;
                    $backendUser->password = md5(Constant::DEFAULT_PASSWORD . $salt);
                    $backendUser->salt = $salt;
                    $backendUser->brid = intval($params['backend_user_brid']);
                    $backendUser->status = Constant::VALID_STATUS;
                    $backendUser->create_time = date('Y-m-d H:i:s');
                    $backendUser->update_time = date('Y-m-d H:i:s');
                    $affectedRows = intval($backendUser->save());
                    $jsonArray = [
                        'data' => [
                            'code' => $affectedRows,
                            'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                    ? JsMessage::ADD_SUCCESS : JsMessage::ADD_FAIL,
                        ],
                    ];
                } catch (\Exception $e) {
                    Util::handleException($e, 'Error From addBackendUser');
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

    public function actionModifyBackendUser()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $buid = intval($params['backend_user_buid']);
            if ($buid > Constant::INVALID_PRIMARY_ID) {
                $name = trim($params['backend_user_name']);
                if (!BackendUser::isUserNameExist($name, $buid)) {
                    try {
                        $backendUser = BackendUser::findOne($buid);
                        if ($backendUser instanceof BackendUser) {
                            $backendUser->name = $name;
                            $backendUser->brid = intval($params['backend_user_brid']);
                            $backendUser->update_time = date('Y-m-d H:i:s');
                            $affectedRows = intval($backendUser->save());
                        }
                        $jsonArray = [
                            'data' => [
                                'code' => $affectedRows,
                                'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                        ? JsMessage::MODIFY_SUCCESS : JsMessage::MODIFY_FAIL,
                            ],
                        ];
                    } catch (\Exception $e) {
                        Util::handleException($e, 'Error From modifyBackendUser');
                    }
                } else {
                    $jsonArray = [
                        'data' => [
                            'code' => Constant::INIT_AFFECTED_ROWS,
                            'message' => JsMessage::ACCOUNT_EXIST,
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

    public function actionDeleteBackendUser()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $buid = isset($params['buid']) ? intval($params['buid']) : Constant::INVALID_PRIMARY_ID;
            try {
                $backendUser = BackendUser::findOne($buid);
                if ($backendUser instanceof BackendUser) {
                    $backendUser->status = Constant::INVALID_STATUS;
                    $backendUser->update_time = date('Y-m-d H:i:s');
                    $affectedRows = intval($backendUser->save());
                }
                $jsonArray = [
                    'data' => [
                        'code' => $affectedRows,
                        'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                            ? JsMessage::DELETE_SUCCESS : JsMessage::DELETE_FAIL,
                    ],
                ];
            } catch (\Exception $e) {
                Util::handleException($e, 'Error From deleteBackendUser');
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

    public function actionRecoverBackendUser()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            try {
                $affectedRows = Constant::INIT_AFFECTED_ROWS;
                $params = yii::$app->request->post('params', array());
                $buid = isset($params['buid']) ? intval($params['buid']) : Constant::INVALID_PRIMARY_ID;
                if ($buid > Constant::INVALID_PRIMARY_ID) {
                    $backendUser = BackendUser::findOne($buid);
                    if ($backendUser instanceof BackendUser) {
                        $backendUser->status = Constant::VALID_STATUS;
                        $backendUser->update_time = date('Y-m-d H:i:s');
                        $affectedRows = intval($backendUser->save());
                    }
                    $jsonArray = [
                        'data' => [
                            'code' => $affectedRows,
                            'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                                    ? JsMessage::RECOVER_ACCOUNT_SUCCESS : JsMessage::RECOVER_ACCOUNT_FAIL,
                        ]
                    ];

                }
            } catch (\Exception $e) {
                Util::handleException($e, 'Error From recoverBackendUser');
            }
        }

        if (!isset($jsonArray['data'])) {
            $jsonArray = [
                'error' => Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO),
            ];
        }

        echo json_encode($jsonArray);
    }

    public function actionGetBackendUser()
    {
        if (yii::$app->request->isGet) {
            $params = yii::$app->request->get('params', array());
            $buid = (isset($params['buid'])) ? intval($params['buid']) : Constant::INVALID_PRIMARY_ID;
            $data = BackendUser::getBackendUserByID($buid);
            if (!empty($data)) {
                $jsonArray = [
                    'data' => [
                        'buid' => $data['buid'],
                        'name' => $data['name'],
                        'brid' => $data['brid'],
                        'roles' => BackendRole::getAllRoles(),
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

    private function _index()
    {
        $params = yii::$app->request->get('params', array());
        list($currentPage, $pageLength, $start) = Util::getPaginationParamsFromUrlParamsArray($params);
        $keyword = isset($params['keyword']) ? trim($params['keyword']) : '';
        $tabType = isset($params['tab_type']) ? intval($params['tab_type']) : 1;

        $conditions = [
            ['status' => $tabType],
            ['!=', 'name', Constant::ADMIN_NAME],
        ];
        if ($keyword !== '') {
            $conditions[] = ['like', 'name', Util::getLikeString($keyword), false];
        }
        $orderBy = ['buid' => SORT_DESC];
        $total = BackendUser::getSearchCount($conditions);
        $data = BackendUser::getSearchData($conditions, $start, $pageLength, $orderBy);
        $roles = BackendRole::getAllRoles();
        $output = [];
        foreach ($data as $value) {
            $output[] = [
                'buid' => $value['buid'],
                'name' => $value['name'],
                'role' => isset($roles[$value['brid']]) ? $roles[$value['brid']] : '-',
            ];
        }

        $jsonData = [
            'data' => [
                'totalPages' => Util::getTotalPages($total, $pageLength),
                'pageIndex' => $currentPage,
                'totalItems' => $total,
                'startIndex' => $start + 1,
                'itemsPerPage' => $pageLength,
                'currentItemCount' => count($data),
                'items' => $output,
            ],
        ];
        return $jsonData;
    }

}