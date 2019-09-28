<?php

namespace ant\subscription\migrations\db;

use common\components\Migration;

/**
 * Class M190715104331_create_subscription_bundle
 */
class M190715104331_create_subscription_bundle extends Migration
{
	protected $tableName = '{{%subscription_bundle}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'price' => $this->decimal(12, 2)->notNull(),
            'package_id' => $this->integer(255)->unsigned(),
			'invoice_id' => $this->integer(10)->unsigned()->null()->defaultValue(null),
            'created_at' => $this->timestamp()->defaultValue(null),
        ],  $this->getTableOptions());
		
		$this->addForeignKeyTo('{{%payment_invoice}}', 'invoice_id', self::FK_TYPE_SET_NULL, self::FK_TYPE_SET_NULL);
    }

    /**
     * {@inheritdoc}
     */
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
        echo "M190715104331_create_subscription_bundle cannot be reverted.\n";

        return false;
    }
    */
}
