<?php

namespace backend\modules\subscription\controllers;

use Yii;
use common\modules\subscription\models\SubscriptionPackage;
use common\modules\subscription\models\SubscriptionPackageItem;
use common\modules\subscription\models\SubscriptionPackageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/**
 * SubscriptionPackageController implements the CRUD actions for SubscriptionPackage model.
 */
class SubscriptionPackageController extends \ant\subscription\backend\controllers\SubscriptionPackageController
{
}
