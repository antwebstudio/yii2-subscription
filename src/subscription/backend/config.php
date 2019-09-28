<?php

return [
    'id' => 'subscription',
    'class' => \ant\subscription\backend\Module::className(),
    'isCoreModule' => false,
	'depends' => [], // Payment module should not depends on any other module
];
?>