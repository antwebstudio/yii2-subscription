<?php
if (YII_DEBUG) throw new \Exception('DEPRECATED, please use subscription/backend/subscription instead. '); // 2019-10-18
?>
<?= \yii\grid\GridView::widget([
	'dataProvider' => $dataProvider,
	'columns' => [
		'subscriptionPackage.id',
		[
			'label' => 'Subscribed Package',
			'attribute' => 'subscriptionPackage.name',
		],
		'expire_at',
		[
			'format' => 'html',
			'attribute' => 'statusHtml',
		],
	],
]) ?>