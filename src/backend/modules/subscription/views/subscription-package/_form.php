<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\TabularInput;

/* @var $this yii\web\View */
/* @var $model common\modules\subscription\models\SubscriptionPackage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscription-package-form">

    <?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
    ]); ?>
    <?= $form->field($model, 'subscription_identity')->widget(kartik\select2\Select2::classname(), [
                'data' => $this->context->module->subscriptionPackageIdentiy,
                //yii\helpers\ArrayHelper::map(User::find()->all(), 'id', 'username'),
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => '',
                    'multiple' => false
                ],
                'pluginOptions' => ['allowClear' => true],
            ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
	<?= TabularInput::widget([
	    'models' => $items,
	    'form' => $form,
	    'max' => $this->context->module->subscriptionPackageFormMax,
	    'enableError' => true,
	    'attributeOptions' => [
	        'enableAjaxValidation'      => true,
	        'enableClientValidation'    => false,
	        'validateOnChange'          => true,
	        'validateOnSubmit'          => true,
	        'validateOnBlur'            => false,
	    ],
	    'columns' => $this->context->module->getSubscriptionPackageFormColumns(),
	]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
