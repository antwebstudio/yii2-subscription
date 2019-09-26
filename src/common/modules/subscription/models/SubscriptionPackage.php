<?php

namespace common\modules\subscription\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\modules\payment\models\Invoice;
use common\modules\payment\models\InvoiceItem;
use common\modules\payment\models\BillableItem;
use common\modules\contact\models\Contact;
/**
 * This is the model class for table "em_subscription_package".
 *
 * @property string $id
 * @property string $subscription_identity
 * @property string $name
 * @property string $price
 * @property string $created_at
 * @property string $updated_at
 * @property integer $app_id
 *
 * @property SubscriptionPackageItem[] $subscriptionPackageItems
 */
class SubscriptionPackage extends \yii\db\ActiveRecord implements BillableItem
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subscription_package}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subscription_identity', 'name', 'price'], 'required'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['app_id'], 'integer'],
            [['subscription_identity'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
        ];
    }

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subscription_identity' => 'Subscription Identity',
            'name' => 'Name',
            'price' => 'Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'app_id' => 'App ID',
        ];
    }
	
	public function getPackageItems() {
        return $this->hasMany(SubscriptionPackageItem::className(), ['package_id' => 'id']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionPackageItems()
    {
        return $this->getPackageItems();
	}

	public function getIncludedInSubtotal() {
		return true;
	}

	public function getUnitPrice() {
		return $this->price;
	}
	
	public function getTitle() {
		return $this->name;
	}

	public function getDiscountedUnitPrice() {
		return $this->price;
	}

	public function getQuantity() {
		return 1;
	}

	public function getDescription() {
		
	}

	public function getId() {
		return $this->id;
	}

	public function getDiscount() {
		return 0;
	}

	public function setDiscount($discount, $discountType = 0) {
		
	}

    public function updateSubscriptionPackageHistory(){
        $itemName = '';
        $subscriptionPacksageItems = $this->subscriptionPackageItems;
        foreach ($subscriptionPacksageItems as $key => $item) {
            $itemName .= '<br/>- ' . $item->name;
        }
        $model = new SubscriptionPackageHistory(
            ['name' => $this->name . $itemName, 'price' => $this->price, 'package_id' => $this->id]
        );
        $model->save();
    }
	
	public function subscribe($user, $startDateTime = null) {
		if (!$user->id) throw new \Exception('User is not an valid user. ');
		
		$items = SubscriptionPackageItem::findAll(['package_id' => $this->id]);
		
		$transaction = Yii::$app->db->beginTransaction();
		
		try {
			$invoice = $this->createInvoice($user);
			
			$bundle = new SubscriptionBundle;
			$bundle->attributes = $this->attributes;
			$bundle->invoice_id = $invoice->id;
			$bundle->package_id = $this->id;
			if (!$bundle->save()) throw new \Exception('Failed to create bundle. ');
			
			foreach ($items as $item) {
				
				$subscription = new Subscription;
				$subscription->bundle_id = $bundle->id;
				
				$subscription->attributes = [
					'subscription_identity' => $item->subscription_identity,
					'price' => $this->price,
					'purchased_unit' => 1,
					'used_unit' => 0,
					'content_valid_days' => $item->content_valid_days,
					'invoice_id' => $invoice->id,					
				];
				$subscription->package_id = $this->id;
				$subscription->owned_by = $user->id;
				$subscription->setExpireAtDays($item->content_valid_days, true, $startDateTime);
				
				if (!$subscription->save()) throw new \Exception('Failed to create subscription. '.print_r($subscription->errors, 1));
			}
			
			$transaction->commit();
			
			return $bundle;
		} catch (\Exception $ex) {
			$transaction->rollback();
			throw $ex;
		}
	}
	
	public function createInvoice($user) {
		if (!isset($user->profile->contact)) throw new \Exception('Cannot issue invoice to user without default contact info. ');

		return Invoice::createFromBillableItem($this, $user->profile->contact);
	}

}
