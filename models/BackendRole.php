<?php

namespace app\models;

use Yii;
use app\library\bill\Constant;
/**
 * This is the model class for table "backend_role".
 *
 * @property string $brid
 * @property string $role
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class BackendRole extends BillActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'backend_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['role'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'brid' => 'Brid',
            'role' => 'Role',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getBackendRoleByID($brid)
    {
        return BackendRole::find()
            ->where(['brid' => $brid])
            ->asArray()
            ->one();
    }

    public static function isRoleExist($role, $brid)
    {
        $count = BackendRole::find()
            ->where(['role' => $role])
            ->andWhere(['!=', 'brid', $brid])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->count();
        return intval($count) === 0 ? false : true;
    }

    public static function getAllRoles()
    {
        $data = BackendRole::find()
            ->select(['brid', 'role'])
            ->where(['status' => Constant::VALID_STATUS])
            ->asArray()->all();
        $roles = [];
        foreach ($data as $value) {
            $roles[$value['brid']] = $value['role'];
        }

        return $roles;
    }
}