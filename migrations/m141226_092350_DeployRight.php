<?php

use yii\db\Schema;
use yii\db\Migration;

class m141226_092350_DeployRight extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%deploy_right}}', [
            'user_id' => Schema::TYPE_INTEGER,
            'project_id' => Schema::TYPE_INTEGER,
            'PRIMARY KEY(`user_id`, `project_id`)'
        ], $tableOptions);

        $this->addForeignKey(
            'FK_deploy_right_user', '{{%deploy_right}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE'
        );
        $this->addForeignKey(
            'FK_deploy_right_project', '{{%deploy_right}}', 'project_id', '{{%project}}', 'id', 'CASCADE', 'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%deploy_right}}');

        return false;
    }
}
