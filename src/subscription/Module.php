<?php

namespace ant\subscription;

/**
 * subscribe module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
	
	public function behaviors() {
		return [
			'configurable' => [
				'class' => 'ant\behaviors\ConfigurableModuleBehavior'
			],
		];
	}
	
	public function formModels() {
		return [
			'subscription' => [
				'class' => 'ant\subscription\models\SubscriptionForm',
			],
		];
	}
}
