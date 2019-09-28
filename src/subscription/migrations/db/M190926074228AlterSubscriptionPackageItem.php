<?php

namespace ant\subscription\migrations\db;

use yii\db\Migration;

/**
 * Class M190926074228AlterSubscription
 */
class M190926074228AlterSubscriptionPackageItem extends Migration
{
	protected $tableName = '{{%subscription_package_item}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->renameColumn($this->tableName, 'subscription_unit', 'unit');
		
		$this->renameColumn($this->tableName, 'subscription_days', 'valid_period');
		$this->alterColumn($this->tableName, 'valid_period', $this->smallInteger(3)->null()->defaultValue(null));
		$this->addColumn($this->tableName, 'valid_period_type', $this->smallInteger()->null()->defaultValue(null)->after('valid_period')); // Refer to ant/helpers/RecurringHelper, 3 mean daily

		$this->renameColumn($this->tableName, 'content_valid_days', 'content_valid_period');
		$this->alterColumn($this->tableName, 'content_valid_period', $this->smallInteger(3)->null()->defaultValue(null));
		$this->addColumn($this->tableName, 'content_valid_period_type', $this->smallInteger()->null()->defaultValue(null)->after('content_valid_period')); // Refer to ant/helpers/RecurringHelper, 3 mean daily
		
		$this->update($this->tableName, ['valid_period_type' => 3]);
		$this->update($this->tableName, ['content_valid_period_type' => 3]);		
		
		$this->addColumn($this->tableName, 'priority', $this->smallInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->renameColumn($this->tableName, 'unit', 'subscription_unit');
		
		$this->renameColumn($this->tableName, 'valid_period', 'subscription_days');
		$this->alterColumn($this->tableName, 'subscription_days', $this->smallInteger(3)->null());
		$this->dropColumn($this->tableName, 'valid_period_type');

		$this->renameColumn($this->tableName, 'content_valid_period', 'content_valid_days');
		$this->alterColumn($this->tableName, 'content_valid_days', $this->smallInteger(3)->null());
		$this->dropColumn($this->tableName, 'content_valid_period_type');
		
		$this->dropColumn($this->tableName, 'priority');
		
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190926074228AlterSubscription cannot be reverted.\n";

        return false;
    }
    */
}
