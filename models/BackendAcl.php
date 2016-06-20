<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\library\bill\Constant;
use app\library\bill\Util;
/**
 * This is the model class for table "backend_acl".
 *
 * @property string $baid
 * @property string $name
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class BackendAcl extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'backend_acl';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['module', 'controller', 'action'], 'string', 'max' => 100],
            [['module', 'controller', 'action'], 'unique', 'targetAttribute' => ['module', 'controller', 'action'], 'message' => 'The combination of Module, Controller and Action has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'baid' => 'Baid',
            'name' => 'Name',
            'module' => 'Module',
            'controller' => 'Controller',
            'action' => 'Action',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getBackendAclCount(array $conditions)
    {
        $select = BackendAcl::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
        }
        $count = $select->count();
        return $count;
    }

    public static function getBackendAclData(array $conditions, $start, $pageLength, $orderBy, $groupBy)
    {
        $select = BackendAcl::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
        }
        $data = $select
            ->limit($pageLength)
            ->offset($start)
            ->orderBy($orderBy)
            ->groupBy($groupBy)
            ->asArray()
            ->all();
        return $data;
    }

    public static function getBackendAclByID($baid)
    {
        return BackendAcl::find()
            ->where(['baid' => $baid])
            ->asArray()
            ->one();
    }

    public static function isAclExist($module, $controller, $action)
    {
        $count = BackendAcl::find()
            ->where(['module' => $module])
            ->andWhere(['controller' => $controller])
            ->andWhere(['action' => $action])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->count();
        return intval($count) === 0 ? false : true;
    }

    public static function getAclID($module, $controller, $action)
    {
        $data = BackendAcl::find()
            ->select('baid')
            ->where(['module' => $module])
            ->andWhere(['controller' => $controller])
            ->andWhere(['action' => $action])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->asArray()->one();
        return isset($data['baid']) ? $data['baid'] : Constant::INVALID_PRIMARY_ID;
    }

    public static function getInvalidActionsAclIDs($module, $controller, array $validActions)
    {
        $select = BackendAcl::find()
            ->select('baid')
            ->where(['module' => $module])
            ->andWhere(['controller' => $controller])
            ->andWhere(['status' => Constant::VALID_STATUS]);
        if (!empty($validActions)) {
            $select->where(['not in', 'action', $validActions]);
        }
        $data = $select
            ->asArray()->all();
        $baids = [];
        foreach ($data as $value) {
            $baids[] = $value['baid'];
        }

        return $baids;
    }

    public static function getInvalidControllersAclIDs($module, array $validControllers)
    {
        $select = BackendAcl::find()
            ->select('baid')
            ->where(['module' => $module])
            ->andWhere(['status' => Constant::VALID_STATUS]);
        if (!empty($validControllers)) {
            $select->andWhere(['not in', 'controller', $validControllers]);
        }
        $data = $select
            ->asArray()->all();
        $baids = [];
        foreach ($data as $value) {
            $baids[] = $value['baid'];
        }

        return $baids;
    }

    public static function getAclList()
    {
        $data = BackendAcl::find()
            ->select(['module', 'controller', 'action', 'baid'])
            ->where(['status' => Constant::VALID_STATUS])
            ->groupBy(['module', 'controller', 'action'])
            ->asArray()->all();
        $acl = [];
        foreach ($data as $value) {
            $module = $value['module'];
            $controller = $value['controller'];
            $action = $value['action'];
            if (isset($acl[$module])) {
                if (!isset($acl[$module][$controller])) {
                    $acl[$module][$controller] = [];
                }
                $acl[$module][$controller][] = [
                    'action' => $action,
                    'id' => $value['baid'],
                ];
            } else {
                $acl[$module] = [
                    $controller => [
                        [
                            'action' => $action,
                            'id' => $value['baid'],
                        ]
                    ]
                ];
            }
        }

        return $acl;
    }

    public static function getAclMap()
    {
        $data = BackendAcl::find()
            ->select(['module', 'controller', 'action', 'baid'])
            ->where(['status' => Constant::VALID_STATUS])
            ->groupBy(['module', 'controller', 'action'])
            ->asArray()->all();
        $map = [];
        foreach ($data as $value) {
            $aclMapKey = Util::getAclMapKey($value['module'], $value['controller'], $value['action']);
            $map[$aclMapKey] = $value['baid'];
        }

        return $map;
    }
}