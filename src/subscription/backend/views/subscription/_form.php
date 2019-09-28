<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use trntv\filekit\widget\Upload;
use common\modules\user\models\User;
use common\modules\subscription\models\SubscriptionPackage;
$customDate = [];
/* @var $this yii\web\View */
/* @var $model common\modules\subscription\models\Subscription */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscription-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php if ($model->errors): ?>
        <?= \yii\bootstrap\Alert::widget([
            'options' => ['class' => 'alert alert-danger'],
            'body' => $form->errorSummary([$model, $invoice, $order]),
        ]) ?>
    <?php endif ?>

    <?php // $form->field($model, 'app_id')->textInput() ?>

    <?php // $form->field($model, 'subscription_identity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?php //$form->field($model, 'purchased_unit')->textInput() ?>

    <?php // $form->field($model, 'used_unit')->textInput() ?>

    <?php // $form->field($model, 'content_valid_days')->textInput() ?>

    <?php // $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'owned_by')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
            'maintainOrder' => true,
            'options' => [
                'placeholder' => '',
                'multiple' => true
            ],
            'pluginOptions' => ['allowClear' => true],
        ]);
    ?>

    <?=
         $form->field($model, 'content_valid_days')->widget(Select2::classname(), [
            'data' => ArrayHelper::merge([30 => 30, 90 => 90, 365 => 365], $customDate),
            'maintainOrder' => true,
            'options' => [
                'placeholder' => '', 'multiple' => false
            ],
            'pluginOptions' => [
                'tags' => true,
                'allowClear' => true
            ],
        ])->label('Valid Days');
    ?>

    <?php // '<label class="control-label">Subscription Days</label>'; ?>
    <?php 
        // Select2::widget([
        //     'name' => 'validDay',
        //     'data' => ArrayHelper::merge([30 => 30, 90 => 90, 365 => 365], $customDate),
        //     'options' => [
        //         'placeholder' => '',
        //         'multiple' => true
        //     ],
        //     'pluginOptions' => [
        //         'tags' => true,
        //         'allowClear' => true
        //     ],
        // ]);
    ?>
    <p class="hint">
        Hint: You can type custom day(s) instead of from the list.
    </p>

    <?php // $form->field($model, 'subscription_identity')->textInput(['maxlength' => true]) ?>
    <?= $form->field($order, 'event_id')->widget(Select2::classname(), [
                'data' => isset($this->context->module->subscriptionFormData) ? $this->context->module->subscriptionFormData : $this->context->module->getDefaultSubscriptionFormData(),
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => '', 'multiple' => false
                ],
                'pluginOptions' => ['allowClear' => true],
        ])->label('Subscription Package Title');
    ?>
    <p class="hint">
        Hint: This field can be blank.
    </p>

    <?= $form->field($invoice, 'remark')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'invoice_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
