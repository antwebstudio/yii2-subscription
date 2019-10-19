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