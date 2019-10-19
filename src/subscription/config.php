<?php
return [
    'id' => 'subscription',
    'class' => \ant\subscription\Module::className(),
    'isCoreModule' => false,
	'modules' => [
		//'v1' => \ant\booking\api\v1\Module::class,
		'backend' => \ant\subscription\backend\Module::class,
	],
	'depends' => [],
];