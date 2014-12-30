<?php

namespace app\models;

use Yii;
use yii\db\Expression;

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
            [['command', 'active_status', 'username', 'password', 'host'], 'string'],
            [['category_id', 'last_user_deploy_id'], 'integer'],
            [['last_deploy_date'], 'safe'],
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

    public function setDeployDate()
    {
        $this->last_deploy_date = new Expression('NOW()');
        $this->save();
    }

    public function getKey()
    {
        return md5($this->username.'sl43'.$this->password);
    }

    public function checkKey($key)
    {
        return $this->getKey()==$key;
    }

    public function createParams()
    {

    }

    public function beforeValidate()
    {
        // очистим пробельные символы
        if(parent::beforeValidate())
        {
            $command = explode(PHP_EOL, $this->command);
            foreach($command as &$value)
            {
                $value = trim($value);
            }
            $this->command = implode(PHP_EOL, $command);
            return true;
        }
        return false;
    }
}
