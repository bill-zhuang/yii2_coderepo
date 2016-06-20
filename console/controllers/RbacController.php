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
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // add "adminPermission" permission
        $adminPermission = $auth->createPermission('adminPermission');
        $adminPermission->description = 'Manage backend user';
        $auth->add($adminPermission);

        // add "admin" role and give this role the "adminPermission" permission
        // as well as the permissions of the "normal" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $adminPermission);

        // Assign roles to users. admin_id/normal_id are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        //add admin
        $adminID = $this->_addUserAdmin();
        if ($adminID != 0) {
            //add role
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('admin');
            $auth->assign($authorRole, $adminID);
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