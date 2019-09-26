<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

/**
 * User fixture
 */
class SubscriptionPackageFixture extends ActiveFixture
{
    public $modelClass = 'common\modules\subscription\models\SubscriptionPackage';
	public $depends = [
		'tests\fixtures\SubscriptionPackageItemFixture',
	];
}
