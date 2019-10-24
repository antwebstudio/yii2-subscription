<?php

namespace ant\subscription\migrations\db;

use ant\db\Migration;

class M180508_000007_create_subscription_package extends Migration
{
	protected $tableName = '{{%subscription_package}}';
	
    public function safeUp()
    {
		$this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'subscription_identity' => $this->string(50)->notNull(),
            'name' => $this->string(100)->notNull(),
            'price' => $this->decimal(12, 2)->notNull(),
            'created_at' => $this->timestamp()->defaultValue(null),
            'updated_at' => $this->timestamp()->defaultValue(null),
            'app_id' => $this->smallInteger(4)->unsigned()->defaultValue(null),
        ],  $this->getTableOptions());
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171122142203Create_securitymobileapp_schedule_clocking cannot be reverted.\n";

        return false;
    }
    */
}
