<?= \yii\grid\GridView::widget([
	'dataProvider' => $dataProvider,
	'columns' => [
		'subscriptionPackage.id',
		'subscriptionPackage.name',
		'expire_at',
		[
			'format' => 'html',
			'attribute' => 'statusHtml',
		],
	],
]) ?>