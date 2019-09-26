<?php
namespace common\modules\subscription\migrations\db;

use common\components\Migration;

class m180607_080512_alter_subscription extends Migration
{
	protected $tableName = '{{%subscription}}';
    public function safeUp()
    {
		$this->addColumn('{{%subscription}}', 'package_id', $this->integer()->unsigned()->null()->defaultValue(null));
        $this->addForeignKeyTo('{{%subscription_package}}', 'package_id', self::FK_TYPE_SET_NULL, self::FK_TYPE_SET_NULL);

    }

    public function safeDown()
    {
        $this->dropForeignKeyTo('{{%subscription_package}}', 'package_id');
        $this->dropColumn('{{%subscription}}', 'package_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170731_080512_alter_article_category cannot be reverted.\n";

        return false;
    }
    */
}
