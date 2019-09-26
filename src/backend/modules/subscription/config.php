<?php

return [
    'id' => 'subscription',
    'class' => \backend\modules\subscription\Module::className(),
    'isCoreModule' => false,
	'depends' => [], // Payment module should not depends on any other module
];
?>