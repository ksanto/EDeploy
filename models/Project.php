<?php

namespace app\models;

use Yii;

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
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'command'], 'required'],
            [['command', 'active_status'], 'string'],
            [['category_id'], 'integer'],
            [['last_deploy_date'], 'safe'],
            [['title'], 'string', 'max' => 255]
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
            'category_id' => 'Category ID',
            'active_status' => 'Active Status',
            'last_deploy_date' => 'Last Deploy Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getStatusList()
    {
        return array(
            self::STATUS_ACTIVE     => 'Active',
            self::STATUS_NOT_ACTIVE => 'Not active'
        );
    }
}