<?php

namespace common\modules\subscription\models;

use Yii;

/**
 * This is the model class for table "{{%subscription_package_history}}".
 *
 * @property string $id
 * @property string $name
 * @property string $price
 * @property string $package_id
 *
 * @property Subscription[] $subscriptions
 * @property SubscriptionPackage $package
 */
class SubscriptionPackageHistory extends \ant\subscription\models\SubscriptionPackageHistory
{
}
