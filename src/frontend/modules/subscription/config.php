<?php

return [
    'id' => 'subscription',
    'class' => \frontend\modules\subscription\Module::className(),
    'isCoreModule' => false,
	'depends' => [], // Payment module should not depends on any other module
];
?>