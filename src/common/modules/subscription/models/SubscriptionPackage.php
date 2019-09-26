<?php

namespace common\modules\subscription\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\modules\payment\models\Invoice;
use common\modules\payment\models\InvoiceItem;
use common\modules\payment\models\BillableItem;
use common\modules\contact\models\Contact;
/**
 * This is the model class for table "em_subscription_package".
 *
 * @property string $id
 * @property string $subscription_identity
 * @property string $name
 * @property string $price
 * @property string $created_at
 * @property string $updated_at
 * @property integer $app_id
 *
 * @property SubscriptionPackageItem[] $subscriptionPackageItems
 */
class SubscriptionPackage extends \ant\subscription\models\SubscriptionPackage
{
}
