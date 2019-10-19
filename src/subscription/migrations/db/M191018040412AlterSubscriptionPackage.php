<?php

namespace ant\subscription\migrations\db;

use yii\db\Migration;

/**
 * Class M191018040412AlterSubscriptionPackage
 */
class M191018040412AlterSubscriptionPackage extends Migration
{
	protected $tableName = '{{%subscription_package}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn($this->tableName, 'show_in_signup', $this->boolean()->notNull()->defaultValue(1));
		$this->addColumn($this->tableName, 'short_description', $this->string()->null());
		$this->addColumn($this->tableName, 'admin_note', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn($this->tableName, 'show_in_signup');
		$this->dropColumn($this->tableName, 'short_description');
		$this->dropColumn($this->tableName, 'admin_note');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191018040412AlterSubscriptionPackage cannot be reverted.\n";

        return false;
    }
    */
}
