<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "backend_user".
 *
 * @property string $bu_id
 * @property string $bu_name
 * @property string $bu_password_hash
 * @property string $bu_auth_key
 * @property string $bu_role
 * @property integer $bu_status
 * @property string $bu_create_time
 * @property string $bu_update_time
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
            [['bu_password_hash', 'bu_auth_key', 'bu_role'], 'required'],
            [['bu_role', 'bu_status'], 'integer'],
            [['bu_create_time', 'bu_update_time'], 'safe'],
            [['bu_name'], 'string', 'max' => 128],
            [['bu_password_hash', 'bu_auth_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bu_id' => 'Bu ID',
            'bu_name' => 'Bu Name',
            'bu_password_hash' => 'Bu Password Hash',
            'bu_auth_key' => 'Bu Auth Key',
            'bu_role' => 'Bu Role',
            'bu_status' => 'Bu Status',
            'bu_create_time' => 'Bu Create Time',
            'bu_update_time' => 'Bu Update Time',
        ];
    }

    public static function getBackendUserCount(array $conditions)
    {
        $select = BackendUser::find();
        foreach ($conditions as $key => $content)
        {
            $select->andWhere([$content['compare_type'], $key, $content['value']]);
        }
        $count = $select->count();
        return $count;
    }

    public static function getBackendUserData(array $conditions, $limit, $offset, $order_by)
    {
        $select = BackendUser::find();
        foreach ($conditions as $key => $content)
        {
            $select->andWhere([$content['compare_type'], $key, $content['value']]);
        }
        $data = $select
            ->limit($limit)
            ->offset($offset)
            ->orderBy($order_by)
            ->asArray()
            ->all();
        return $data;
    }

    public static function getBackendUserByID($bu_id)
    {
        return BackendUser::find()
            ->where(['bu_id' => $bu_id])
            ->asArray()
            ->one();
    }

    public static function getUserInfo($user_name)
    {
        return BackendUser::find()
            ->where(['bu_name' => $user_name])
            ->andWhere(['bu_status' => 1])
            ->asArray()
            ->one();
    }

    public function isUserNameExist($name, $buid)
    {
        $count = BackendUser::find()
            ->where(['bu_name' => $name])
            ->andWhere(['!=', 'bu_id', $buid])
            ->andWhere(['bu_status' => 1])
            ->count();
        return ($count === 0) ? false : true;
    }
}