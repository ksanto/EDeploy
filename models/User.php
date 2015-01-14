<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const ADMIN     = 1;
    const NOT_ADMIN = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password', 'auth_key'], 'string'],
            [['is_admin'], 'in', 'range' => array_keys($this->getPermissionList())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'User Name',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'is_admin' => 'Is Admin',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
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
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getPermission()
    {
        $status = $this->getPermissionList();
        if(!isset($status[$this->is_admin]))
            return false;

        return $status[$this->is_admin];
    }

    public function getPermissionList()
    {
        return array(
            self::ADMIN     => 'Yes',
            self::NOT_ADMIN => 'No'
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasMany(DeployRight::className(), ['user_id' => 'id']);
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if($this->password) {
                $this->password     = Yii::$app->security->generatePasswordHash($this->password);
                $this->auth_key     = Yii::$app->security->generateRandomString();
            } else {
                // Если пароль не установили, то остается старый пароль
                unset($this->password);
            }
            return true;
        }
        return false;
    }
}
