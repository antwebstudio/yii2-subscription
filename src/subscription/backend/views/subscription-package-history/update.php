<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ant\subscription\models\SubscriptionPackageHistory */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Subscription Package History',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subscription Package Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="subscription-package-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
