<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model ant\subscription\models\Subscription */

$this->title = Yii::t('app', 'Subscription');
$this->params['title'] = $this->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subscriptions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-create">
	<p>Selected Package: </p>
	
	<?= $this->render('_package', ['package' => $model->package, 'actionButtonLabel' => false]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
