<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

/**
 * User fixture
 */
class SubscriptionPackageFixture extends ActiveFixture
{
    public $modelClass = 'ant\subscription\models\SubscriptionPackage';
	public $depends = [
		'tests\fixtures\SubscriptionPackageItemFixture',
	];
}
