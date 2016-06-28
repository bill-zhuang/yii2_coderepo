<?php

namespace app\controllers;

use app\models\BackendRoleAcl;
use yii;
use app\models\BackendAcl;
use app\library\bill\Constant;
use app\library\bill\Util;
use app\library\bill\JsMessage;
use yii\web\Response;

class BackendAclController extends BillController
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

    public function actionLoadBackendAcl()
    {
        $jsonArray = [];
        $affectedRows = Constant::INIT_AFFECTED_ROWS;
        $defaultControllerDir = \Yii::$app->basePath . '/controllers/';
        $moduleDir = \Yii::$app->basePath . '/modules/';
        //default
        $this->_loadAcl2DB('', $defaultControllerDir);
        //modules
        if (is_dir($moduleDir)) {
            $modules = scandir($moduleDir);
            foreach ($modules as $module) {
                if ($module != '.' && $module != '..' && is_dir($moduleDir . $module . DIRECTORY_SEPARATOR)) {
                    $controllerDirPath = $moduleDir . $module . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR;
                    if (is_dir($controllerDirPath)) {
                        $affectedRows += $this->_loadAcl2DB(strtolower($module), $controllerDirPath);
                    }
                }
            }
            $jsonArray = [
                'data' => [
                    'code' => $affectedRows,
                    'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                            ? JsMessage::LOAD_ACL_SUCCESS : JsMessage::LOAD_ACL_NO_ACL_LOADED,
                ],
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

    private function _loadAcl2DB($moduleName, $controllerPath)
    {
        $affectedRows = Constant::INIT_AFFECTED_ROWS;
        $pregController = '/.*?Controller.php$/';
        $pregController_postfix = '/Controller.php$/';
        $pregAction = '/public\s+function\s+action(.*?)\(\)/';
        $pregAction_postfix = '/^action/';
        $data = [
            'name' => '',
            'module' => '',
            'controller' => '',
            'action' => '',
            'status' => Constant::VALID_STATUS,
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s'),
        ];

        if (is_dir($controllerPath)) {
            $validControllers = [];
            $controllers = scandir($controllerPath);
            foreach ($controllers as $controller) {
                if ($controller != '.' && $controller != '..') {
                    if (preg_match($pregController, $controller) !== 0) {
                        $controllerName = preg_replace($pregController_postfix, '', $controller);
                        $controllerName = strtolower(implode('-', $this->_splitCamel($controllerName)));
                        $controllerContent = file_get_contents($controllerPath . $controller);
                        $isMatch = preg_match_all($pregAction, $controllerContent, $actionMatches);
                        $data['module'] = $moduleName;
                        $data['controller'] = $controllerName;
                        $validControllers[] = $controllerName;
                        $validActions = [];
                        if ($isMatch) {
                            foreach ($actionMatches[1] as $action) {
                                if (ucfirst($action) != $action) {
                                    //action first letter uppercase
                                    continue;
                                }
                                $actionName = preg_replace($pregAction_postfix, '', $action);
                                $actionName = strtolower(implode('-', $this->_splitCamel($actionName)));
                                $data['action'] = $actionName;
                                $validActions[] = $actionName;
                                if (!BackendAcl::isAclExist($data['module'], $data['controller'], $data['action'])) {
                                    $data['name'] = ($data['module'] == '' ? '' : '/' . $data['module'])
                                        . '/' . $data['controller'] . '/' . $data['action'];
                                    $backendAcl = new BackendAcl();
                                    foreach ($data as $key => $value) {
                                        $backendAcl->$key = $value;
                                    }
                                    $affectedRows += intval($backendAcl->save());
                                }
                            }
                        }
                        //delete unused action
                        $this->_removeInvalidAcl($data['module'], $data['controller'], $validActions);
                    }
                }
            }
            //delete unused controller
            $this->_removeInvalidAcl($moduleName, $validControllers, array());
        }

        return $affectedRows;
    }

    private function _removeInvalidAcl($module, $controller, array $validActions)
    {
        if (!is_array($controller)) {
            $invalidAclIds = BackendAcl::getInvalidActionsAclIDs($module, $controller, $validActions);
        } else {
            $invalidAclIds = BackendAcl::getInvalidControllersAclIDs($module, $controller);
        }
        if (!empty($invalidAclIds)) {
            $transaction = BackendAcl::getDb()->beginTransaction();
            try {
                $deleteWhere = ['in', 'baid', $invalidAclIds];
                BackendAcl::deleteAll($deleteWhere);
                BackendRoleAcl::deleteAll($deleteWhere);
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }
    }

    private function _splitCamel($controller)
    {
        $pregController = '/([A-Z][a-z\d]*)/';
        $isMatch = preg_match_all($pregController, ucfirst($controller), $matches);
        if ($isMatch) {
            return $matches[1];
        } else {
            return [];
        }
    }
}