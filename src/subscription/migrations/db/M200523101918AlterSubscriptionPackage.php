<?php

namespace ant\subscription\migrations\db;

use yii\db\Migration;

/**
 * Class M200523101918AlterSubscriptionPackage
 */
class M200523101918AlterSubscriptionPackage extends Migration
{
	protected $tableName = '{{%subscription_package}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn($this->tableName, 'options', $this->text()->null()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn($this->tableName, 'options');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200523101918AlterSubscriptionPackage cannot be reverted.\n";

        return false;
    }
    */
}
