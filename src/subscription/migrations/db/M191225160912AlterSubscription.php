<?php

namespace ant\subscription\migrations\db;

use ant\db\Migration;

/**
 * Class M191225160912AlterSubscription
 */
class M191225160912AlterSubscription extends Migration
{
	protected $tableName = '{{%subscription}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'item_id', $this->integer()->unsigned()->null()->defaultValue(null));
		$this->addColumn($this->tableName, 'item_class_id', $this->integer()->unsigned()->null()->defaultValue(null));
        $this->addForeignKeyTo('{{%model_class}}', 'item_class_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropForeignKeyTo('{{%model_class}', 'item_class_id');
		$this->dropColumn($this->tableName, 'item_id');
		$this->dropColumn($this->tableName, 'item_class_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191225160912AlterSubscription cannot be reverted.\n";

        return false;
    }
    */
}
