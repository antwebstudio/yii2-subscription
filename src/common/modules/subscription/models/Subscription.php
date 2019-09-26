<?php

namespace common\modules\subscription\models;

use Yii;
use common\modules\payment\models\Invoice;
use common\modules\user\models\User;
use yii\behaviors\BlameableBehavior;
use common\helpers\DateTime;
use common\behaviors\TimestampBehavior;
/**
 * This is the model class for table "em_subscription".
 *
 * @property string $id
 * @property string $subscription_identity
 * @property string $price
 * @property integer $purchased_unit
 * @property integer $used_unit
 * @property integer $content_valid_days
 * @property integer $status
 * @property integer $owned_by
 * @property string $expire_date
 * @property string $created_at
 * @property string $updated_at
 * @property string $invoice_id
 * @property integer $app_id
 *
 * @property Invoice $invoice
 */
class Subscription extends \ant\subscription\models\Subscription
{
}
