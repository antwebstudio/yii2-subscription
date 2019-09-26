<?php

namespace common\modules\subscription\migrations\db;

use common\components\Migration;

class M180607_000008_create_subscription_package_history extends Migration
{
	protected $tableName = '{{%subscription_package_history}}';
	
    public function safeUp()
    {
		$this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'price' => $this->decimal(12, 2)->notNull(),
            'package_id' => $this->integer(255)->unsigned(),
            'created_at' => $this->timestamp()->defaultValue(null),
        ],  $this->getTableOptions());
        $this->addForeignKeyTo('{{%subscription_package}}', 'package_id', self::FK_TYPE_SET_NULL, self::FK_TYPE_SET_NULL);
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
