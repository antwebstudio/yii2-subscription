<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\subscription\models\SubscriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Subscriptions Payment History');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'invoice Id',
                'attribute' => 'invoice_id',
            ],
            //'app_id',
            [
                'label' => 'Package',
                'attribute' => 'packageName',
                'value' => function ($model) {
                    return isset($model->subscriptionPackage->name) ? $model->subscriptionPackage->name : null;
                },
                'format' => 'html',
            ],
            [
                'label' => 'Package Price',
                'attribute' => 'packagePrice',
                'value' => function ($model) {
                    return isset($model->subscriptionPackage->price) ? $model->subscriptionPackage->price : null;
                },
                'format' => 'html',
            ],
            [
                'label' => 'Actual Pay',
                'attribute' => 'actualPay',
                'value' => function ($model) {
                    $invoice = isset($model->invoice) ? $model->invoice : null;
                    if ($invoice) {
                        return $invoice->paid_amount;
                    } else {
                        return null;
                    }
                },
                'format' => 'html',
            ],
            [
                'label' => 'Last Payment Date',
                'value' => function($model) {
                    $payments = isset($model->invoice->payments) ? $model->invoice->payments : null;
                    if ($payments == null) {
                        return 'No payment history';
                    } else {
                        foreach ($payments as $key => $payment) {

                        }
                        return $payment->created_at; 
                    }
                },
            ],
            // [
            //     'label' => 'Subscribed item',
            //     'attribute' => 'subscription_identity',
            //     'value' => function($model) {
            //         return $model->subscription_identity;
            //     }
            // ],
            // 'price',
            // 'created_at',
            // [
            //     'label' => 'Expire Date',
            //     'attribute' => 'expire_date',
            //     'value' => function($model) {
            //         return date('Y-m-d', strtotime($model->expire_date));
            //     }
            // ],
            // //'purchased_unit',
            // // 'used_unit',
            // // 'content_valid_days',
            // // 'status',
            [
                'label' => 'Owner Username',
                'attribute' => 'username',
                'value' => function($model) {
                    return Html::a($model->user->username, ['/subscribe/subscribed-member/view', 'id' => $model->owned_by]);
                },
                'format' => 'html',
            ],
            // 'id',
            // 'expire_date',
            // 'created_date',
            // 'last_updated',
            // 'invoice_id',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
