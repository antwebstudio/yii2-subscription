<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model ant\subscription\models\SubscriptionPackageHistory */

$this->title = Yii::t('app', 'Create Subscription Package History');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subscription Package Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-package-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
