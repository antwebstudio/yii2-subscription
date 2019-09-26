<?php

namespace common\modules\subscription\models;

use Yii;
use common\modules\payment\models\Invoice;
use common\modules\user\models\User;
use yii\behaviors\BlameableBehavior;
use common\helpers\DateTime;
use common\behaviors\TimestampBehavior;
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
 * @property string $expire_date
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
            [['subscription_identity', 'price', 'purchased_unit', 'used_unit', 'content_valid_days', 'owned_by', 'invoice_id'], 'required'],
            [['price'], 'number'],
            [['purchased_unit', 'used_unit', 'content_valid_days', 'status', 'owned_by', 'invoice_id', 'app_id'], 'integer'],
            [['expire_date', 'created_at', 'updated_at'], 'safe'],
            [['status'], 'default', 'value' => 0],
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
            'expire_date' => 'Expire Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'invoice_id' => 'Invoice ID',
            'app_id' => 'App ID',
        ];
    }
	
	public function getStatusHtml() {
		if ($this->isExpired) {
			return '<span class="label label-warning">Expired</span>';
		}
		return '<span class="label label-success">Active</span>';
	}
	
	public function getIsExpired() {
		if (isset($this->expire_date)/* && !$this->expire_date->getIsNull()*/) {
			$now = new DateTime();
			$expireDate = new DateTime($this->expire_date);
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

    public function getSubscriptionPackage()
    {
        return $this->hasOne(SubscriptionPackage::className(), ['id' => 'package_id']);
    }

    public static function find() {
        return new \common\modules\subscription\models\query\SubscriptionQuery(get_called_class());
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'owned_by']);
    }
	
	// Set expire date as x days after today
	public function setExpireAtDays($day, $setTimeAsEndOfDay = true, $fromDateTime = null) {
		$fromDateTime = isset($fromDateTime) ? new DateTime($fromDateTime) : new DateTime;
		$this->expire_date = $fromDateTime->addDays($day)->setTimeAsEndOfDay($setTimeAsEndOfDay);
	}

    public static function createExpireDate($userId, $day = null){
        if ($day == null) {
            $day = 31;
        } else {
            $day += 1;
        }
        $existedSubscription = self::find()->currentlyActiveForUser($userId)->one();
        if ($existedSubscription) {
            $expireDate = date('Y-m-d 00:00:00', strtotime($existedSubscription->expire_date . " +". $day ." days"));
        } else {
            $expireDate = date('Y-m-d 00:00:00', strtotime(" +". $day ." days"));
        }
        return $expireDate;
    }
}
