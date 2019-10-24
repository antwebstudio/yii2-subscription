<?php

namespace ant\subscription\migrations\db;

use ant\db\Migration;

/**
 * Class M190927055055AlterSubscriptionBundle
 */
class M190927055055AlterSubscriptionBundle extends Migration
{
	protected $tableName = '{{%subscription_bundle}}';	
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn($this->tableName, 'organization_id', $this->integer()->unsigned()->null()->defaultValue(null));
		$this->addColumn($this->tableName, 'owned_by', $this->integer()->unsigned()->null()->defaultValue(null));
		$this->addForeignKeyTo('{{%organization}}', 'organization_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropForeignKeyTo('{{%organization}}', 'organization_id');
		$this->dropForeignKeyTo('{{%organization}}', 'owned_by');
		$this->dropColumn($this->tableName, 'organization_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190927055055AlterSubscriptionBundle cannot be reverted.\n";

        return false;
    }
    */
}
