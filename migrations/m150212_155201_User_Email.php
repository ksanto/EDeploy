<?php

use yii\db\Schema;
use yii\db\Migration;

class m150212_155201_User_Email extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'email', Schema::TYPE_STRING . ' DEFAULT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'email');
    }
}
