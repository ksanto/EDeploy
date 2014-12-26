<?php

namespace app\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const ADMIN     = 1;
    const NOT_ADMIN = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'auth_key', 'access_token'], 'required'],
            [['username', 'password', 'auth_key', 'access_token'], 'string'],
            [['is_admin'], 'integer'],
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
            'access_token' => 'Access Token',
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
        return static::findOne(['access_token' => $token]);
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
        return $this->password === md5($password);
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

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if($this->password) {
                $this->password     = md5($this->password);
                $this->auth_key     = md5(str_shuffle($this->password));
                $this->access_token = md5(str_shuffle($this->password));
            } else {
                // Если пароль не установили, то остается старый пароль
                unset($this->password);
            }
            return true;
        }
        return false;
    }
}
