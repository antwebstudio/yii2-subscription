<?php

namespace ant\subscription\migrations\db;

use yii\db\Migration;

/**
 * Class M190926144948AlterSubscription
 */
class M190926144948AlterSubscription extends Migration
{
	protected $tableName = '{{%subscription}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {	
		$this->renameColumn($this->tableName, 'expire_date', 'expire_at');
		$this->renameColumn($this->tableName, 'content_valid_days', 'content_valid_period');
		$this->alterColumn($this->tableName, 'content_valid_period', $this->smallInteger(3)->null()->defaultValue(null));
		$this->addColumn($this->tableName, 'content_valid_period_type', $this->smallInteger()->notNull()->defaultValue(3)->after('content_valid_period')); // Refer to ant/helpers/RecurringHelper, 3 mean daily
		$this->addColumn($this->tableName, 'priority', $this->smallInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->renameColumn($this->tableName, 'expire_at', 'expire_date');
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
        echo "M190926144948AlterSubscription cannot be reverted.\n";

        return false;
    }
    */
}
