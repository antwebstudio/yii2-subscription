<?php
if (YII_DEBUG) throw new \Exception('DEPRECATED, please use subscription/backend/subscription instead. '); // 2019-10-18


use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\subscription\models\SubscriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Subscriptions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Subscription'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            //'id',
            //'app_id',
            [
                'label' => 'Subscribed item',
                'attribute' => 'subscription_identity',
                'value' => function($model) {
                    return $model->subscription_identity;
                }
            ],
            //'price',
            'created_at',
            [
                'label' => 'Expire Date',
                'attribute' => 'expire_at',
                'value' => function($model) {
                    return date('Y-m-d', strtotime($model->expire_at));
                }
            ],
            //'purchased_unit',
            // 'used_unit',
            // 'content_valid_days',
            // 'status',
            [
                'label' => 'Owner Username',
                'attribute' => 'user.username',
                'value' => function($model) {
                    return $model->user->username;
                }
            ],
            [
                'label' => 'Extra Information',
                'value' => function($model) {
                    return Html::a('Extra Information', Url::to(['/subscription/subscription-history/index', 'id' => $model->id]), ['class' => 'btn btn-primary'])
                    ;
                },
                'format' => 'html',
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
