<?php

namespace ant\subscription\models\query;

use yii\db\Expression;
use ant\subscription\models\Subscription;
use ant\payment\models\Invoice;
use Yii;

class SubscriptionQuery extends \yii\db\ActiveQuery {
	// bool overlap = a.start < b.end && b.start < a.end;
	// Reference: https://stackoverflow.com/questions/13513932/algorithm-to-detect-overlapping-periods
	public function isExpire() {
		return $this->andWhere(['owned_by' => Yii::$app->user->id])
            ->andWhere(['>', 'expire_at', date('Y-m-d H:i:s')]);
	}
	
	public function lastExpirePaidOrFreeSubscription() {
		return $this->orderBy('expire_at DESC')->isPaidOrFree();
	}
	
	public function ownedByOrganization($organization) {
		$organization = $organization instanceof Organization ? $organization->id : $organization;
		
		return $this->joinWith('organization organization')
			->andWhere(['organization.id' => $organization]);
	}
	
	public function ownedBy($userId) {
		return $this->andWhere(['owned_by' => $userId]);
	}
	
	public function isPaid() {
		return $this->joinWith('invoice invoice')
			->andWhere(['invoice.status' => [Invoice::STATUS_PAID, Invoice::STATUS_PAID_MANUALLY]]);
	}
	
	public function isPaidOrFree() {
		return $this->joinWith('invoice invoice')
			->andWhere(['OR', 
				['invoice.id' => null],
				['invoice.status' => [Invoice::STATUS_PAID, Invoice::STATUS_PAID_MANUALLY]],
				['invoice.total_amount' => 0],
			]);
	}
	
	public function active() {
		return $this->notExpired()->isPaidOrFree();
	}
	
	public function notExpired() {
		return $this->andWhere(['or', ['>', 'expire_at', date('Y-m-d H:i:s')], ['expire_at' => null]]);
	}
	
	public function currentlyActiveForUser($userId) {
		return $this->andWhere(['owned_by' => $userId])
			->andWhere(['or', ['>', 'expire_at', date('Y-m-d H:i:s')], ['expire_at' => null]]);
	}

	public function type($type) {
		return $this->andWhere(['subscription_identity' => $type]);
	}
}