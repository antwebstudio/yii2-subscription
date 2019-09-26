<?php

namespace ant\subscription\models\query;

use yii\db\Expression;
use common\modules\subscription\models\Subscription;
use common\modules\payment\models\Invoice;
use Yii;

class SubscriptionQuery extends \yii\db\ActiveQuery {
	// bool overlap = a.start < b.end && b.start < a.end;
	// Reference: https://stackoverflow.com/questions/13513932/algorithm-to-detect-overlapping-periods
	public function isExpire() {
		return $this->andWhere(['owned_by' => Yii::$app->user->id])
            ->andWhere(['>', 'expire_date', date('Y-m-d H:i:s')]);
	}
	
	public function ownedBy($userId) {
		return $this->andWhere(['owned_by' => $userId]);
	}
	
	public function isPaid() {
		return $this->joinWith('invoice invoice')
			->andWhere(['invoice.status' => [Invoice::STATUS_PAID, Invoice::STATUS_PAID_MANUALLY]]);
	}
	
	public function currentlyActiveForUser($userId) {
		return $this->andWhere(['owned_by' => $userId])
			->andWhere(['or', ['>', 'expire_date', date('Y-m-d H:i:s')], ['expire_date' => null]]);
	}

	public function type($type) {
		return $this->andWhere(['subscription_identity' => $type]);
	}
}