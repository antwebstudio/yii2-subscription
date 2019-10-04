<?php

namespace ant\subscription\migrations\db;

use common\components\Migration;

class M180508_000006_create_subscription extends Migration
{
	protected $tableName = '{{%subscription}}';
	
    public function safeUp()
    {
		$this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'subscription_identity' => $this->string(50)->notNull(),
            'price' => $this->decimal(12, 2)->notNull(),
            'purchased_unit' => $this->smallInteger(3)->notNull(),
            'used_unit' => $this->smallInteger(3)->notNull(),
            'content_valid_days' => $this->smallInteger(3)->notNull(),
            'status' => $this->integer(12)->notNull(),
            'owned_by' => $this->integer(11)->notNull(),
            'expire_date' => $this->timestamp()->null()->defaultValue(null),
            'created_at' => $this->timestamp()->null()->defaultValue(null),
            'updated_at' => $this->timestamp()->null()->defaultValue(null),
            'invoice_id' => $this->integer(255)->unsigned()->null()->defaultValue(null),
            'app_id' => $this->smallInteger(4)->unsigned()->defaultValue(null),
        ],  $this->getTableOptions());
            $this->addForeignKeyTo('{{%payment_invoice}}', 'invoice_id', self::FK_TYPE_CASCADE, self::FK_TYPE_RESTRICT);
    }

    public function safeDown()
    {
        $this->dropForeignKeyTo('{{%payment_invoice}}', 'invoice_id');
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
