<?php
namespace ant\subscription\behaviors;

use ant\models\ModelClass;
use ant\subscription\models\Subscription;

class SubscribableBehavior extends \yii\base\Behavior {
    public function isSubscribedBy($ownerId) {
        return $this->getActiveSubscriptionsByUser($ownerId)->count();
    }
	
	protected function getActiveSubscriptionsByUser($ownerId) {
		return $this->getActiveSubscriptions()->andWhere(['owned_by' => $ownerId]);
	}

    public function getActiveSubscriptions() {
        return $this->getSubscriptions()->active();
    }

    public function getSubscriptions() {
        return $this->owner->hasMany(Subscription::class, ['item_id' => 'id'])
            ->onCondition(['item_class_id' => ModelClass::getClassId($this->owner)]);
    }
}