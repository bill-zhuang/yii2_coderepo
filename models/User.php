<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
            [['salt', 'brid'], 'required'],
            [['brid', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['password', 'salt'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     * 
     * @return \app\models\User|null
     */
    public static function findIdentity($id)
    {
        return static::findOne(['buid' => $id, 'status' => self::STATUS_VALID]);
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
        return static::findOne(['name' => $username, 'status' => self::STATUS_VALID]);
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
        return $this->salt;
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
        return $this->password == md5($password . $this->getAuthKey());
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = md5($password . $this->getAuthKey());
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->salt = Yii::$app->security->generateRandomString();
    }

}
