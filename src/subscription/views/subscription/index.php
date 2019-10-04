<?php

use yii\helpers\Html;
use yii\grid\GridView;
use ant\subscription\models\Subscription;
use common\modules\organization\models\Organization;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\subscription\models\SubscriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Subscriptions');
$this->params['title'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-index">

	<?php /*
	Balance Credit: <?= Yii::$app->subscription->getCreditBalance(Yii::$app->user->id) ?>
	*/?>
	
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'layout' => "{items}\n{pager}",
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			[
				'label' => 'Purchase Date',
				'attribute' => 'created_at',
			],
			[
				'attribute' => 'expire_at',
				'value' => function($model) {
					return isset($model->expire_at) ? date('Y-m-d', strtotime($model->expire_at)) : ' - ';
				}
			],
			[
			  'attribute' => 'isExpired',
			  'label' => 'Status',
			  'value' => function($model) {
				return $model->isExpired ? 'Expired' : 'Active';
			  }
			],
			'price',
		],
	]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'app_id',
            'subscription_identity',
            'price',
            'purchased_unit',
            // 'used_unit',
            // 'content_valid_days',
            // 'status',
            // 'owned_by',
            // 'expire_date',
            // 'created_date',
            // 'last_updated',
            // 'invoice_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
