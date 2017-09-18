<?php

namespace app\console\controllers;

use app\library\bill\Constant;
use app\models\BackendRole;
use Yii;
use yii\console\Controller;
use app\models\User;
class RbacController extends Controller
{
    public function actionInit()
    {
        //add admin
        $adminID = $this->_addUserAdmin();
        if ($adminID != 0) {
            echo 'Add user account successfully.';
        } else {
            echo 'Add user account failed';
        }
    }

    private function _addUserAdmin()
    {
        $user_name = 'admin';
        return $this->_addUser($user_name);
    }

    private function _addUser($name)
    {
        $transaction = User::getDb()->beginTransaction();
        //add role
        $role = new BackendRole();
        $role->role = 'admin';
        $role->status = Constant::VALID_STATUS;
        $role->create_time = date('Y-m-d H:i:s');
        $role->update_time = date('Y-m-d H:i:s');
        $role->save();
        $brid = $role->brid;
        //add user
        $user = new User();
        $user->name = $name;
        $user->generateAuthKey();
        $user->setPassword(Constant::DEFAULT_PASSWORD);
        $user->brid = $brid;
        $user->status = Constant::VALID_STATUS;
        $user->create_time = date('Y-m-d H:i:s');
        $user->update_time = date('Y-m-d H:i:s');
        if ($user->save()) {
            $transaction->commit();
            return $user->getId();
        } else {
            $transaction->rollBack();
        }

        return Constant::INVALID_PRIMARY_ID;
    }

}