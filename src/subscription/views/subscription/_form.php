<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\subscription\models\Subscription */
/* @var $form yii\widgets\ActiveForm */
$models = $model->getModels();
$models[] = $model;

$paymentMethods = [];
foreach (\Yii::$app->payment->getAllPaymentMethodFor($model->package) as $paymentMethod) {
	$paymentMethods[$paymentMethod->name] = $paymentMethod->name;
}
?>

<div class="subscription-form">

    <?php $form = ActiveForm::begin() ?>
		<?= $form->errorSummary($models, ['class' => 'alert alert-danger']) ?>
		
		<?= $form->field($model, 'packageId')->hiddenInput()->label(false) ?>
		
		<?php if (count($paymentMethods) > 1): ?>
			<?= $form->field($model, 'paymentMethod')->dropDownList($paymentMethods, ['prompt' => '']) ?>
		<?php elseif (count($paymentMethods) == 1): ?>
			<?= $form->field($model, 'paymentMethod')->hiddenInput(['value' => current($paymentMethods)])->label(false) ?>
		<?php endif ?>

		<div class="form-group submit text-center text-md-left">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Confirm') : Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
		</div>

    <?php ActiveForm::end() ?>

</div>
