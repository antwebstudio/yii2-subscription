<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\subscription\models\SubscriptionPackage */

$this->title = Yii::t('app', 'Create Subscription Package');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subscription Packages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-package-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>
