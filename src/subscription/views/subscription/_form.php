<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\subscription\models\Subscription */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscription-form">

    <?php $form = ActiveForm::begin() ?>
		<?= $form->errorSummary($model->getModels(), ['class' => 'alert alert-danger']) ?>
		
		<?= $form->field($model, 'packageId')->hiddenInput()->label(false) ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Confirm') : Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
		</div>

    <?php ActiveForm::end() ?>

</div>
