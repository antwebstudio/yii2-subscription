<?php

namespace common\modules\subscription\models;

use Yii;
use common\modules\payment\models\Invoice;

/**
 * This is the model class for table "ks_subscription_bundle".
 *
 * @property int $id
 * @property string $name
 * @property string $price
 * @property int $package_id
 * @property string $created_at
 *
 * @property Subscription[] $subscriptions
 */
class SubscriptionBundle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subscription_bundle}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price'], 'required'],
            [['price'], 'number'],
            [['package_id'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'package_id' => 'Package ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Subscription::className(), ['bundle_id' => 'id']);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
	public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }
}
