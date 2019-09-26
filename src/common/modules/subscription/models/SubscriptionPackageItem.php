<?php

namespace common\modules\subscription\models;

use Yii;
use common\behaviors\TimestampBehavior;
/**
 * This is the model class for table "em_subscription_package_item".
 *
 * @property string $id
 * @property string $subscription_identity
 * @property string $name
 * @property integer $subscription_unit
 * @property integer $subscription_days
 * @property integer $content_valid_days
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $package_id
 *
 * @property SubscriptionPackage $package
 */
class SubscriptionPackageItem extends \ant\subscription\models\SubscriptionPackageItem
{
}
