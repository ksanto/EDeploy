<?php

use yii\db\Schema;
use yii\db\Migration;

class m141215_125334_User extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'password' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . ' NOT NULL',
            'access_token' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);

        // create default user
        $this->insert('{{%user}}', [
            'username'      => 'admin',
            'password'      => md5('admin'),
            'auth_key'      => md5(str_shuffle('admin')),
            'access_token'  => md5(str_shuffle('admin'))
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
