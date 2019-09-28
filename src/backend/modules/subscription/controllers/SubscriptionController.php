<?php

namespace backend\modules\subscription\controllers;

use Yii;
use common\modules\payment\models\Invoice;
use common\modules\payment\models\InvoiceItem;
use common\modules\contact\models\Contact;
use common\modules\order\models\Order;
use common\modules\user\models\User;
use common\modules\subscription\models\Subscription;
use common\modules\subscription\models\SubscriptionPackage;
use common\modules\subscription\models\SubscriptionPackageHistory;
use common\modules\subscription\models\SubscriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubscriptionController implements the CRUD actions for Subscription model.
 */
class SubscriptionController extends \ant\subscription\backend\controllers\SubscriptionController
{
}
