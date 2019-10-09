<?php
return [
	[
		'package_id' => 1,
		'name' => 'test item',
		'unit' => 2,
		'valid_period' => 3,
		'content_valid_period' => 4,
		'status' => 0,
		'subscription_identity' => 'test',
	],
	'freeTrial' => [
		'package_id' => 2,
		'name' => '2 week free trial',
		'unit' => 2,
		'valid_period_type' => 4, // week
		'valid_period' => 2, // 2 weeks
		'content_valid_period' => 4,
		'status' => 0,
		'subscription_identity' => 'test',
	],
];