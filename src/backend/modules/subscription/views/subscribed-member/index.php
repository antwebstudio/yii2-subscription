<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\subscription\models\Subscription;

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
        'layout' => "{summary}\n<div class=\"table-responsive\">{items}</div>\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // //'name',
            // 'description',
            // 'created_at',
            // 'created_by',
            [
                'label' => 'Username',
                'attribute' => 'username',
            ],
            [
                'label' => 'Address',
                'attribute' => 'profile.address',
            ],
            [
                'label' => 'Contact',
                'attribute' => 'profile.contact',
            ],
            [
                'label' => 'Company Name',
                'attribute' => 'profile.company',
            ],
            [
                'label' => 'Company Registration Number',
                'attribute' => 'profile.data.company_registration_number',
            ],
            [
                'label' => 'Position',
                'attribute' => 'profile.data.position',
            ],
            [
                'label' => 'Full Name',
                'attribute' => 'profile.fullname',
            ],
            [
                'label' => 'Fax',
                'attribute' => 'profile.data.fax',
            ],
            [
                'label' => 'Email',
                'attribute' => 'email',
            ],
            [
                'label' => 'Last Subscription Price',
                'attribute' => 'price',
                'value' => function($model) {
                    $subscription = Subscription::find()->andWhere(['owned_by' => $model->id])->orderBy(['created_at' => SORT_DESC])->one();
                    if ($subscription) {
                        return $subscription->price;
                    } else {
                        return 'No record';
                    }
                }
            ],
            [
                'label' => 'Last Subscription',
                'attribute' => 'expireDate',
                'value' => function($model) {
                    $subscription = Subscription::find()->andWhere(['owned_by' => $model->id])->orderBy(['created_at' => SORT_DESC])->one();
                    if ($subscription) {
                        return date('Y-m-d', strtotime($subscription->expire_at));
                    } else {
                        return 'No record';
                    }
                }
            ],            
            /*[
                'label' => 'Last Subscription Period',
                'attribute' => 'content_valid_days',
                'value' => function($model) {
                    $subscription = Subscription::find()->andWhere(['owned_by' => $model->id])->one();
                    if ($subscription) {
                        return $subscription->content_valid_days;
                    } else {
                        return 'No record';
                    }
                }
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
</div>
