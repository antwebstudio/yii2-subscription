<?php

namespace common\modules\subscription\models;

use Yii;
use common\modules\payment\models\Invoice;

/**
 * This is the model class for table "ks_subscription_bundle".
 *
 * @property int $id
 * @property string $name
 * @property string $price
 * @property int $package_id
 * @property string $created_at
 *
 * @property Subscription[] $subscriptions
 */
class SubscriptionBundle extends \ant\subscription\models\SubscriptionBundle
{
}
