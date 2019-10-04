<?php
namespace ant\subscription\models;

use ant\subscription\models\SubscriptionPackage;

class SubscriptionForm extends \ant\base\FormModel {
	public $packageId;
	public $isNewRecord = true;
	
	public function init() {
		$this->package = SubscriptionPackage::findOne($this->packageId);
	}
	
	public function models() {
		return [
			'package:readonly' => [
				'class' => 'ant\subscription\models\SubscriptionPackage',
			],
			'organization' => [
				'class' => 'common\modules\organization\models\Organization',
			],
			'subscriptionBundle:readonly' => [
				'class' => 'ant\subscription\models\SubscriptionBundle',
			],
		];
	}
	
	public function rules() {
		return [
			[['packageId'], 'required'],
		];
	}
	
	public function beforeCommit() {
		$package = SubscriptionPackage::findOne($this->packageId);
		
		$latest = $this->getLastExpireAndPaidSubscription($package->subscription_identity);
		
		$this->subscriptionBundle = $package->subscribe($this->organization, $latest->isExpired ? null : $latest->expire_at); // If already expired then extend rom now, if haven't then extend from expire date
		//if (!isset($bundle)) throw new \Exception('error');
		
		//throw new \Exception($bundle->id);
		return true;
	}
	
	protected function getLastExpireAndPaidSubscription($type) {
        $subscription = Subscription::find()->type($type)
			->ownedByOrganization($this->organization)
			//->active()
			->orderBy('expire_at DESC')
			->one();
			
		return $subscription;
	}
}