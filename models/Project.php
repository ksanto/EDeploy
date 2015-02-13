<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $title
 * @property string $command
 * @property integer $category_id
 * @property string $active_status
 * @property string $last_deploy_date
 *
 * @property Category $category
 */
class Project extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE     = 1;
    const STATUS_NOT_ACTIVE = 0;

    public $permissionUser = array();

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'command', 'username', 'host'], 'required'],
            [['category_id', 'last_user_deploy_id', 'active_status'], 'integer'],
            [['last_deploy_date', 'permissionUser'], 'safe'],
            [['title', 'username', 'password', 'host'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'command' => 'Command',
            'category_id' => 'Category',
            'active_status' => 'Active Status',
            'last_deploy_date' => 'Last Deploy Date',
            'last_user_deploy_id' => 'Last User Deploy',
            'username' => 'User Name',
            'password' => 'Password',
            'host' => 'Host',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'last_user_deploy_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermission()
    {
        return $this->hasMany(DeployRight::className(), ['project_id' => 'id']);
    }

    public function getStatus()
    {
        $status = $this->getStatusList();
        if(!isset($status[$this->active_status]))
            return false;

        return $status[$this->active_status];
    }

    public function getStatusList()
    {
        return array(
            self::STATUS_ACTIVE     => 'Active',
            self::STATUS_NOT_ACTIVE => 'Not active'
        );
    }

    public function applyDeployData()
    {
        $this->last_deploy_date     = new Expression('NOW()');
        $this->last_user_deploy_id  = Yii::$app->getUser()->getIdentity()->getId();
        $this->save(false);
    }

    public function getToken()
    {
        return md5($this->username.'sl43'.$this->password);
    }

    public function checkToken($key)
    {
        return $this->getToken()==$key;
    }

    /**
     * Очистка пробельных символов в командах
     */
    public function trimCommand()
    {
        $command = explode(PHP_EOL, $this->command);
        foreach($command as &$value)
        {
            $value = trim($value);
        }
        $this->command = implode(PHP_EOL, $command);
    }

    public function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            $this->trimCommand();
            if($this->password) {
                $this->password     = Yii::$app->getSecurity()->encryptByKey(
                    $this->password,
                    Yii::$app->params['securityKey']
                );
            } else {
                // Если пароль не установили, то остается старый пароль
                $this->password = $this->getOldAttribute('password');
            }
            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        foreach($this->permission as $right)
        {
            $this->permissionUser[] = $right->user_id;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        DeployRight::deleteAll(['project_id' => $this->id]);
        foreach((array)$this->permissionUser as $user)
        {
            $rights = new DeployRight();
            $rights->project_id = $this->id;
            $rights->user_id = $user;
            $rights->save();
        }
    }
}
