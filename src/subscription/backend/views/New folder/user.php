
<?php if ($searchModel->userId): ?>
    <?= \yii\bootstrap\Nav::widget([
        'options' => [
            'class' => 'nav-tabs',
            'style' => 'margin-bottom: 15px'
        ],
        'items' => \Yii::$app->menu->getMenu(\ant\user\Module::MENU_VIEW_PROFILE, ['user' => $searchModel->userId]),
    ]) ?>
<?php endif ?>

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