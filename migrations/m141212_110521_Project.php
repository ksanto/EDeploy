<?php

use yii\db\Schema;
use yii\db\Migration;

class m141212_110521_Project extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%project}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'command' => Schema::TYPE_TEXT . ' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER,
            'active_status' => "enum('1','0') NOT NULL DEFAULT '1'",
            'last_deploy_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'password' => Schema::TYPE_STRING,
            'host' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);

        $this->createIndex('FK_project_category', '{{%project}}', 'category_id');
        $this->addForeignKey(
            'FK_project_category', '{{%project}}', 'category_id', '{{%category}}', 'id', 'SET NULL', 'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%project}}');
    }
}
