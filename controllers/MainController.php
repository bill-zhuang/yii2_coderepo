<?php

namespace app\controllers;

use app\library\bill\Constant;
use app\models\User;
use yii;
use yii\web\Response;
use app\library\bill\JsMessage;
use app\library\bill\Util;

class MainController extends BillController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionModifyPassword()
    {
        if (yii::$app->request->isPost) {
            $userID = Yii::$app->getUser()->getId();
            $params = yii::$app->request->post('params', array());
            $oldPassword = isset($params['old_password']) ? addslashes($params['old_password']) : '';
            $newPassword = isset($params['new_password']) ? addslashes($params['new_password']) : '';
            if ($userID !== null) {
                $affectedRows = Constant::INVALID_PRIMARY_ID;
                $user = User::findIdentity($userID);
                if ($user->validatePassword($oldPassword)) {
                    $user->generateAuthKey();
                    $user->setPassword($newPassword);
                    $user->update_time = date('Y-m-d H:i:s');
                    $affectedRows = $user->save();
                }
                if ($affectedRows > 0) {
                    $identity = Yii::$app->user->identity;
                    $identity = user::findIdentity($identity->getId()); //new identity, password changed
                    yii::$app->user->login($identity);
                }
                $jsonArray['data'] = [
                    'code' => $affectedRows,
                    'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                            ? JsMessage::MODIFY_SUCCESS : JsMessage::MODIFY_FAIL,
                ];
            }

            if (!isset($jsonArray['data']) && !isset($jsonArray['error'])) {
                $jsonArray['error'] = Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO);
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $jsonArray;
        }

        return $this->render('modify-password');
    }
}