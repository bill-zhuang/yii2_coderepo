<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\library\bill\Constant;
/**
 * This is the model class for table "backend_user".
 *
 * @property string $buid
 * @property string $name
 * @property string $password
 * @property string $salt
 * @property string $brid
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class BackendUser extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'backend_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['salt', 'brid'], 'required'],
            [['brid', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['password', 'salt'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'buid' => 'Buid',
            'name' => 'Name',
            'password' => 'Password',
            'salt' => 'Salt',
            'brid' => 'Brid',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getBackendUserCount(array $conditions)
    {
        $select = BackendUser::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
        }
        $count = $select->count();
        return $count;
    }

    public static function getBackendUserData(array $conditions, $limit, $offset, $order_by)
    {
        $select = BackendUser::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
        }
        $data = $select
            ->limit($limit)
            ->offset($offset)
            ->orderBy($order_by)
            ->asArray()
            ->all();
        return $data;
    }

    public static function getBackendUserByID($buid)
    {
        return BackendUser::find()
            ->where(['buid' => $buid])
            ->asArray()
            ->one();
    }

    public static function getUserInfo($user_name)
    {
        return BackendUser::find()
            ->where(['name' => $user_name])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->asArray()
            ->one();
    }

    public static function isUserNameExist($name, $buid)
    {
        $count = BackendUser::find()
            ->where(['name' => $name])
            ->andWhere(['!=', 'buid', $buid])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->count();
        return ($count === 0) ? false : true;
    }

    public static function getUserName($buid)
    {
        $data = BackendUser::find()
            ->select('name')
            ->where(['buid' => $buid])
            ->asArray()->one();
        return isset($data['name']) ? $data['name'] : '';
    }

    public static function getRoleCount($brid)
    {
        $count = BackendUser::find()
            ->where(['brid' => $brid])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->count();

        return $count;
    }
}