<?php
namespace ant\subscription\components;

use yii\helpers\Html;
use ant\subscription\models\Subscription;
use ant\payment\models\Invoice;
use ant\models\ModelClass;

class SubscriptionComponent extends \yii\base\Component {
    public $creditSubscriptionIdentity;
	
	public function getCurrentActiveSubscription() {
		
    }
    
    public function subscribe($subscribable) {
        $subscription = new Subscription;
        $subscription->item_id = $subscribable->id;
        $subscription->item_class_id = ModelClass::getClassId($subscribable);
        $subscription->subscription_identity = 'default';
        $subscription->price = $subscribable->price;
        $subscription->purchased_unit = 1;
        
        if (!$subscription->save()) throw new \Exception(print_r($subscription->errors, 1));

        return $subscription;
    }
	
	public function earnCreditByAmount($ownerId, $amount) {
		$credit = floor($amount);
		return $this->addCredit($ownerId, $credit);
	}

    public function addCredit($ownerId, $credit) {
        $invoice = $this->createInvoice($credit);

        $subscription = new Subscription(['owned_by' => $ownerId]);

        $subscription->detachBehavior('blameable');

        $subscription->attributes = [
            'subscription_identity' => $this->creditSubscriptionIdentity,
            'price' => $credit,
            'purchased_unit' => $credit,
            'used_unit' => 0,
            'content_valid_days' => 0,
            //'owned_by' => $ownerId,
			'priority' => 0,
            'invoice_id' => $invoice->id,
            'expire_at' => null,
        ];
        if (!$subscription->save()) throw new \Exception(Html::errorSummary($subscription).$subscription->owned_by);

        return $subscription;
    }

    public function useCredit($ownerId, $credit) {
        $subscription = Subscription::find()->type($this->creditSubscriptionIdentity)
			->ownedBy($ownerId)
			->orderBy(new \yii\db\Expression('`expire_at` IS NULL, `expire_at` ASC')) // Sort as expire_at in order NULL value as last. Use credit which is going to expire first
			->all();

        if (isset($subscription)) {
            foreach ($subscription as $s) {
                if ($credit > 0) {
                    $leftUnit = $s->purchased_unit - $s->used_unit;

                    if ($credit > $leftUnit) {
                        $s->updateCounters(['used_unit' => $leftUnit]);
                        $credit = $credit - $leftUnit;
                    } else {
                        $s->updateCounters(['used_unit' => $credit]);
                        $credit = 0;
                    }
                }
            }
        }
    }

    public function getCreditBalance($ownerId) {
        $sum = Subscription::find()->type($this->creditSubscriptionIdentity)->currentlyActiveForUser($ownerId)->sum('purchased_unit - used_unit');
        return isset($sum) ? $sum : 0;
    }

    protected function createInvoice($credit) {
        $invoice = new Invoice;
        $invoice->attributes = [
            'total_amount' => $credit,
            'issue_to' => 0,
        ];
        if (!$invoice->save()) throw new \Exception(Html::errorSummary($invoice));

        return $invoice;
    }
}