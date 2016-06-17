<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\library\bill\Constant;
/**
 * This is the model class for table "backend_role_acl".
 *
 * @property string $braid
 * @property string $brid
 * @property string $baid
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class BackendRoleAcl extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'backend_role_acl';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brid', 'baid'], 'required'],
            [['brid', 'baid', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'braid' => 'Braid',
            'brid' => 'Brid',
            'baid' => 'Baid',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getBackendRoleAclCount(array $conditions)
    {
        $select = BackendRoleAcl::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
        }
        $count = $select->count();
        return $count;
    }

    public static function getBackendRoleAclData(array $conditions, $start, $pageLength, $orderBy)
    {
        $select = BackendRoleAcl::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
        }
        $data = $select
            ->limit($pageLength)
            ->offset($start)
            ->orderBy($orderBy)
            ->asArray()
            ->all();
        return $data;
    }

    public static function getBackendRoleAclByID($braid)
    {
        return BackendRoleAcl::find()
            ->where(['braid' => $braid])
            ->asArray()
            ->one();
    }

    public static function getUserAclByBrid($brid)
    {
        $data = BackendRoleAcl::find()
            ->select('baid')
            ->where(['brid' => $brid])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->asArray()->all();
        $baids = [];
        foreach ($data as $value) {
            $baids[] = $value['baid'];
        }

        return $baids;
    }

    public static function isAccessGranted($brid, $baid)
    {
        $data = BackendRoleAcl::find()
            ->select('braid')
            ->where(['brid' => $brid])
            ->andWhere(['baid' => $baid])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->asArray()->one();
        return isset($data['braid']) ? true : false;
    }
}