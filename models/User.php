<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "backend_user".
 *
 * @property string $bu_id
 * @property string $bu_name
 * @property string $bu_password
 * @property string $bu_salt
 * @property string $bu_role
 * @property integer $bu_status
 * @property string $bu_create_time
 * @property string $bu_update_time
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_VALID = 1;

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
            [['bu_name', 'bu_password', 'bu_salt'], 'string'],
            [['bu_role', 'bu_status'], 'integer'],
            [['bu_create_time', 'bu_update_time'], 'safe']
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
            'bu_password' => 'Bu Password',
            'bu_salt' => 'Bu Salt',
            'bu_role' => 'Bu Role',
            'bu_status' => 'Bu Status',
            'bu_create_time' => 'Bu Create Time',
            'bu_update_time' => 'Bu Update Time',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['bu_id' => $id, 'BU_status' => self::STATUS_VALID]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['bu_name' => $username, 'bu_status' => self::STATUS_VALID]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        //return $this->auth_key;
        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //return Yii::$app->security->validatePassword($password, $this->password_hash);
        return $this->bu_password === md5($password);
    }

}
