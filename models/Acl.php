<?php
namespace app\models;

use Yii;
use app\library\bill\Constant;
use app\library\bill\Util;
class Acl
{
    public static function isAccessGranted($module, $controller, $action, $isAjax)
    {
        if ($module == '' && $controller == 'login') {
            return true;
        }

        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->name != Constant::ADMIN_NAME) {
                $baid = self::_getAclID($module, $controller, $action);
                if ($baid > Constant::INVALID_PRIMARY_ID
                    && BackendRoleAcl::isAccessGranted(Yii::$app->user->identity->brid, $baid)
                ) {
                    return true;
                } else {
                    if ($isAjax) {
                        $jsonArray = [
                            'error' => [
                                'message' => '无权限访问',
                            ],
                        ];
                        return $jsonArray;
                    } else {
                        return '/error/no-permission';
                    }
                }
            } else {
                return true;
            }
        } else {
            return '/login/login';
        }
    }

    private static function _getAclID($module, $controller, $action)
    {
        $aclMap = BackendAcl::getAclMap();
        $aclMap_key = Util::getAclMapKey($module, $controller, $action);
        return isset($aclMap[$aclMap_key]) ? $aclMap[$aclMap_key] : Constant::INVALID_PRIMARY_ID;
    }
}