<?php

namespace ant\subscription\models;

use Yii;
use ant\payment\models\Invoice;
use ant\user\models\User;
use ant\organization\models\Organization;
use yii\behaviors\BlameableBehavior;
use ant\helpers\DateTime;
use ant\behaviors\TimestampBehavior;
/**
 * This is the model class for table "em_subscription".
 *
 * @property string $id
 * @property string $subscription_identity
 * @property string $price
 * @property integer $purchased_unit
 * @property integer $used_unit
 * @property integer $content_valid_days
 * @property integer $status
 * @property integer $owned_by
 * @property string $expire_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $invoice_id
 * @property integer $app_id
 *
 * @property Invoice $invoice
 */
class Subscription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const DEFAULT_VALUE_STATUS = 1;

    public static function tableName()
    {
        return '{{%subscription}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subscription_identity', 'price', 'purchased_unit'], 'required'],
            [['price'], 'number'],
            [['purchased_unit', 'used_unit', 'content_valid_period', 'status', 'owned_by', 'invoice_id', 'app_id'], 'integer'],
            [['expire_at', 'created_at', 'updated_at'], 'safe'],
            [['status', 'used_unit', 'priority'], 'default', 'value' => 0],
            [['subscription_identity'], 'string', 'max' => 50],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
        ];
    }

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'owned_by',
                'updatedByAttribute' => null,
				'preserveNonEmptyValues' => true,
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
            'price' => 'Price',
            'purchased_unit' => 'Purchased Unit',
            'used_unit' => 'Used Unit',
            'content_valid_days' => 'Content Valid Days',
            'status' => 'Status',
            'owned_by' => 'Owned By',
            'expire_at' => 'Expire Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'invoice_id' => 'Invoice ID',
            'app_id' => 'App ID',
        ];
    }
	
	public function getStatusHtml() {
		if ($this->isExpired) {
			return '<span class="badge badge-warning">Expired</span>';
		} else if ($this->isActive) {
		    return '<span class="badge badge-success">Active</span>';
        } else {
            return '<span class="badge badge-dark">Pending</span>';
        }
	}
	
	public function getIsFree() {
		return $this->price == 0;
	}
	
	public function getIsActive() {
        return $this->invoice->isPaid;
	}
	
	public function getIsSuspended() {
		throw new \Exception('Not yet implemented');
	}
	
	public function getIsExpired() {
		if (isset($this->expire_at)/* && !$this->expire_at->getIsNull()*/) {
			$now = new DateTime();
			$expireDate = new DateTime($this->expire_at);
			return ($expireDate < $now);
		}
		return false;
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }
	
	public function getPackage() {
		return $this->getSubscriptionPackage();
	}

    public function getSubscriptionPackage()
    {
        return $this->hasOne(SubscriptionPackage::className(), ['id' => 'package_id']);
    }

    public static function find() {
        return new \ant\subscription\models\query\SubscriptionQuery(get_called_class());
    }
	
	public function getBundle() {
		return $this->hasOne(SubscriptionBundle::class, ['id' => 'bundle_id']);
    }
    
    public function getItem() {
        $className = \ant\models\ModelClass::getClassName($this->item_class_id);
        return $this->hasOne($className, ['id' => 'item_id']);
    }
	
	public function getOrganization() {
		return $this->hasOne(Organization::class, ['id' => 'organization_id'])
			->via('bundle');
	}
	
	public function getOwner() {
		return $this->hasOne(User::className(), ['id' => 'owned_by']);
	}

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'owned_by']);
    }
	
	public function getExpireAt() {
		return $this->expire_at;
	}
	
	// Set expire date
	public function setExpireAt($period, $periodType, $setTimeAsEndOfDay = true, $fromDateTime = null) {
		if (isset($periodType) && isset($period)) {
			$expireAt = isset($fromDateTime) ? new DateTime($fromDateTime) : new DateTime;
			$expireAt = \ant\helpers\RecurringHelper::getNextDateTime($expireAt, $period, $periodType);
			if ($setTimeAsEndOfDay) $expireAt->setTimeAsEndOfDay();
			$this->expire_at = $expireAt;
		} else if (!isset($periodType) && !isset($periodType)) {
			$this->expire_at = null;
		} else {
			throw new \Exception('Period type and period must be either both set or both are not set. ');
		}
	}
	
	// Set expire date as x days after today
	public function setExpireAtDays($day, $setTimeAsEndOfDay = true, $fromDateTime = null) {
		$expireAt = isset($fromDateTime) ? new DateTime($fromDateTime) : new DateTime;
		$expireAt->addDays($day);
		if ($setTimeAsEndOfDay) $expireAt->setTimeAsEndOfDay();
		$this->expire_at = $expireAt;
	}

    public static function createExpireDate($userId, $day = null){
        if ($day == null) {
            $day = 31;
        } else {
            $day += 1;
        }
        $existedSubscription = self::find()->currentlyActiveForUser($userId)->one();
        if ($existedSubscription) {
            $expireDate = date('Y-m-d 00:00:00', strtotime($existedSubscription->expire_at . " +". $day ." days"));
        } else {
            $expireDate = date('Y-m-d 00:00:00', strtotime(" +". $day ." days"));
        }
        return $expireDate;
    }
}
