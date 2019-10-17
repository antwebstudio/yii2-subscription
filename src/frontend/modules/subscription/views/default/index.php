<?php
use yii\grid\GridView;
?>

Balance Credit: <?= Yii::$app->subscription->getCreditBalance(Yii::$app->user->id) ?>

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
]); ?>

