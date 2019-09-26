<?php
namespace common\modules\subscription\components;

use yii\helpers\Html;
use common\modules\subscription\models\Subscription;
use common\modules\payment\models\Invoice;

class SubscriptionComponent extends \yii\base\Component {
    public $creditSubscriptionIdentity;

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
            'invoice_id' => $invoice->id,
            'expire_date' => null,
        ];
        if (!$subscription->save()) throw new \Exception(Html::errorSummary($subscription).$subscription->owned_by);

        return $subscription;
    }

    public function useCredit($ownerId, $credit) {
        $subscription = Subscription::find()->type($this->creditSubscriptionIdentity)->ownedBy($ownerId)->orderBy('expire_date ASC')->all();

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