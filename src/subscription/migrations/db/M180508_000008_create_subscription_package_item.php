<?php

namespace ant\subscription\migrations\db;

use ant\db\Migration;

class M180508_000008_create_subscription_package_item extends Migration
{
	protected $tableName = '{{%subscription_package_item}}';
	
    public function safeUp()
    {
		$this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'subscription_identity' => $this->string(50)->notNull(),
            'name' => $this->string(100)->notNull(),
            'subscription_unit' => $this->smallInteger(3)->notNull(),
            'subscription_days' => $this->smallInteger(3)->notNull(),
            'content_valid_days' => $this->smallInteger(3)->notNull(),
            'status' => $this->integer(12)->notNull(),
            'created_at' => $this->timestamp()->defaultValue(null),
            'updated_at' => $this->timestamp()->defaultValue(null),
            'package_id' => $this->integer(255)->unsigned()->notNull(),
        ],  $this->getTableOptions());
		
        $this->addForeignKeyTo('{{%subscription_package}}', 'package_id', self::FK_TYPE_CASCADE, self::FK_TYPE_RESTRICT);
    }

    public function safeDown()
    {
        $this->dropForeignKeyTo('{{%subscription_package}}', 'package_id');
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
