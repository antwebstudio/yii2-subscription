<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\subscription\models\SubscriptionPackage */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Subscription Package',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subscription Packages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="subscription-package-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>
