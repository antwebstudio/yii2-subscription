<?php

namespace ant\subscription\models\query;

use Yii;
use yii\db\Expression;

class SubscriptionPackageQuery extends \yii\db\ActiveQuery {
	public function type($type) {
		return $this->andWhere(['subscription_identity' => $type]);
	}
}