<?php

namespace app\console\controllers;

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
        $admin_permission = $auth->createPermission('adminPermission');
        $admin_permission->description = 'Manage backend user';
        $auth->add($admin_permission);

        // add "normalPermission" permission
        $normal_permission = $auth->createPermission('normalPermission');
        $normal_permission->description = 'normal user';
        $auth->add($normal_permission);

        // add "normal" role and give this role the "normalPermission" permission
        $normal = $auth->createRole('normal');
        $auth->add($normal);
        $auth->addChild($normal, $normal_permission);

        // add "admin" role and give this role the "adminPermission" permission
        // as well as the permissions of the "normal" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $admin_permission);
        $auth->addChild($admin, $normal_permission);

        // Assign roles to users. admin_id/normal_id are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        //add admin
        $admin_id = $this->_addUserAdmin();
        if ($admin_id != 0)
        {
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('admin');
            $auth->assign($authorRole, $admin_id);
        }
        //add normal user
        $normal_id = $this->_addUserNormal();
        if ($normal_id != 0)
        {
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('normal');
            $auth->assign($authorRole, $normal_id);
        }
    }

    private function _addUserAdmin()
    {
        $user_name = 'admin';
        return $this->_addUser($user_name);
    }

    private function _addUserNormal()
    {
        $user_name = 'normal';
        return $this->_addUser($user_name);
    }

    private function _addUser($name)
    {
        $user = new User();
        $user->bu_name = $name;
        $user->setPassword(yii::$app->params['init_password']);
        $user->generateAuthKey();
        $user->bu_role = 1;
        $user->bu_status = 1;
        $user->bu_create_time = date('Y-m-d H:i:s');
        $user->bu_update_time = date('Y-m-d H:i:s');
        if ($user->save())
        {
            return $user->getId();
        }

        return 0;
    }

}