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
 * @property string $bu_password_hash
 * @property string $bu_auth_key
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

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['bu_id' => $id, 'bu_status' => self::STATUS_VALID]);
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
        return $this->bu_auth_key;
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
        return Yii::$app->security->validatePassword($password, $this->bu_password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->bu_password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->bu_auth_key = Yii::$app->security->generateRandomString();
    }

}
