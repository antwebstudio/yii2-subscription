<?php

namespace common\modules\subscription\migrations\db;

use common\components\Migration;

/**
 * Class M190715104423_alter_subscription
 */
class M190715104423_alter_subscription extends Migration
{
	protected $tableName = '{{%subscription}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn($this->tableName, 'bundle_id', $this->integer()->unsigned()->defaultValue(null));
		$this->addForeignKeyTo('{{%subscription_bundle}}', 'bundle_id', self::FK_TYPE_SET_NULL, self::FK_TYPE_SET_NULL);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKeyTo('{{%subscription_bundle}}', 'bundle_id');
        $this->dropColumn($this->tableName, 'bundle_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190715104423_alter_subscription cannot be reverted.\n";

        return false;
    }
    */
}
