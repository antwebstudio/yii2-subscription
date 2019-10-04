<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

use common\modules\contact\models\Contact;
use common\modules\address\models\AddressCountry as Country;
use common\modules\address\models\AddressZone;

$this->title = 'Billing Setting';
$this->params['title'] = $this->title;


Yii::configure($model, [
	'as configurable' => [
		'class' => 'common\behaviors\ConfigurableModelBehavior',
		'extraAttributeLabels' => [
			'organization' => 'Billing Name',
		],
	],
]);
?>

<?php if (false && YII_DEBUG): ?>
	<p>Contact ID: <?= $model->id ?></p>
	<p>Address ID: <?= $address->id ?></p>
<?php endif ?>

<?php $form = ActiveForm::begin(['enableClientValidation' => false]) ?>
	<?= $form->errorSummary([$model, $address], ['class' => 'alert alert-danger']) ?>

	<?= $form->field($model, 'organization') ?>
	
	<h4>Billing Address</h4>
	
	<?= $form->field($address, 'address_1') ?>
	
	<div class="row">
		<div class="col">
			<?= $form->field($address, 'city') ?>
		</div>
		<div class="col">
			<?= $form->field($address, 'postcode') ?>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?= $form->field($address, 'country_id')->dropdownlist(ArrayHelper::map(Country::find()->asArray()->all(), 'id', 'name'), [
				'value' => 129, 
				'disabled' => true
			]) ?>
			
			
			<?php /*
			<?= $form->field($address, 'country_id')->widget(Select2::class, [
				'data' => ArrayHelper::map(Country::find()->asArray()->all(), 'id', 'name'),
				'options' => ['placeholder' => ''],
			]) ?>
			*/?>
		</div>
		<div class="col">
			<?= $form->field($address, 'zone_id')->widget(Select2::class, [
				'data' => ArrayHelper::map(AddressZone::find()->andWhere(['country_id' => 129])->asArray()->all(), 'id', 'name'),
				'options' => ['placeholder' => ''],
			]) ?>
		</div>
	</div>
	
	<div class="form-group submit">
		<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
	</div>
<?php ActiveForm::end() ?>
