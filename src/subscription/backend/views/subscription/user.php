<?= \yii\grid\GridView::widget([
	'dataProvider' => $dataProvider,
	'columns' => [
		'subscriptionPackage.id',
		'subscriptionPackage.name',
		'expire_date',
		[
			'format' => 'html',
			'attribute' => 'statusHtml',
		],
	],
]) ?>