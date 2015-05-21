<?php

namespace app\console\controllers;

use Yii;
use yii\console\Controller;
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;//var_dump($auth);exit;
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

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        //$auth->assign($normal, 2);
        $result = $auth->assign($admin, 15);
    }

}