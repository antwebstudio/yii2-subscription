<?php

namespace ant\subscription\models;

use Yii;
use ant\behaviors\TimestampBehavior;
use ant\user\models\User;
use ant\organization\models\Organization;
use ant\organization\models\Organization as OldOrganizationClass;
use ant\payment\models\Invoice;
use ant\payment\models\InvoiceItem;
use ant\payment\models\BillableItem;
use ant\contact\models\Contact;
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
	
	public static function find() {
		return new \ant\subscription\models\query\SubscriptionPackageQuery(get_called_class());
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
            TimestampBehavior::class,
            [
				'class' => \ant\behaviors\SerializableAttribute::class,
				'attributes' => ['options'],
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
	
	// The $user parameter is needed when user is not logged in
	// when user is logged in, and no $user is passwed in as parameter, $subscription->owned_by will get user id from Yii::$app->user->id
	public function subscribe($subscriber, $startDateTime = null, $createInvoice = true, $user = null) {
		if ($this->isNewRecord) throw new \Exception('Subscription package is not yet saved. ');
			
		if ($subscriber instanceof User) {
			$user = $subscriber;
			if (!$user->id) throw new \Exception('User is not an valid user. ');
	
			$billTo = $user;
		} else if ($subscriber instanceof Organization || $subscriber instanceof OldOrganizationClass) {
			$organization = $subscriber;
			if (!$organization->id) throw new \Exception('Organization is not an valid organization. ');
			$billTo = $organization;
		} else {
			throw new \Exception('Subscriber must be instance of either User or Organization. ');
		}
		
		$items = SubscriptionPackageItem::findAll(['package_id' => $this->id]);
		
		$transaction = Yii::$app->db->beginTransaction();
		
		try {
			if ($createInvoice) {
				if (!isset($user->profile->contact)) throw new \Exception('Cannot issue invoice to user (user ID: '.$user->id.') without default contact info. ');

				$invoice = Invoice::createFromBillableItem($this, $billTo);
			}
			
			$bundle = new SubscriptionBundle;
			$bundle->attributes = $this->attributes;
			$bundle->invoice_id = isset($invoice) ? $invoice->id : null;
			$bundle->package_id = $this->id;
			$bundle->organization_id = isset($organization) ? $organization->id : null;
			if (!$bundle->save()) throw new \Exception('Failed to create bundle. '.print_r($bundle->errors, 1));
			
			foreach ($items as $item) {
				
				$subscription = new Subscription;
				$subscription->bundle_id = $bundle->id;
				
				$subscription->attributes = [
					'subscription_identity' => $item->subscription_identity,
					'price' => $this->price,
					'purchased_unit' => $item->unit,
					'used_unit' => 0,
					'content_valid_period' => $item->content_valid_period,
					'content_valid_period_type' => $item->content_valid_period_type,
					'invoice_id' => isset($invoice) ? $invoice->id : null,
					'priority' => $item->priority,
				];
				if (isset($user)) $subscription->owned_by = $user->id;
				
				$subscription->package_id = $this->id;
				$subscription->owned_by = isset($user) ? $user->id : null;
				$subscription->setExpireAt($item->valid_period, $item->valid_period_type, true, $startDateTime);
				
				if (!$subscription->save()) throw new \Exception('Failed to create subscription. '.print_r($subscription->errors, 1));
			}
			
			$transaction->commit();
			
			return $bundle;
		} catch (\Exception $ex) {
			$transaction->rollback();
			throw $ex;
		}
	}

}
