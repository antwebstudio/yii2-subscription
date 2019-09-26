<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\subscription\models\SubscriptionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscription-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'app_id') ?>

    <?= $form->field($model, 'subscription_identity') ?>

    <?= $form->field($model, 'price') ?>

    <?= $form->field($model, 'purchased_unit') ?>

    <?php // echo $form->field($model, 'used_unit') ?>

    <?php // echo $form->field($model, 'content_valid_days') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'owned_by') ?>

    <?php // echo $form->field($model, 'expire_date') ?>

    <?php // echo $form->field($model, 'created_date') ?>

    <?php // echo $form->field($model, 'last_updated') ?>

    <?php // echo $form->field($model, 'invoice_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
