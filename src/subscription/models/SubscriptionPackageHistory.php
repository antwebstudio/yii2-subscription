<?php

namespace ant\subscription\models;

use Yii;

/**
 * This is the model class for table "{{%subscription_package_history}}".
 *
 * @property string $id
 * @property string $name
 * @property string $price
 * @property string $package_id
 *
 * @property Subscription[] $subscriptions
 * @property SubscriptionPackage $package
 */
class SubscriptionPackageHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subscription_package_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price', 'package_id'], 'required'],
            [['price'], 'number'],
            [['package_id'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['package_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubscriptionPackage::className(), 'targetAttribute' => ['package_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'price' => Yii::t('app', 'Price'),
            'package_id' => Yii::t('app', 'Package ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Subscription::className(), ['package_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionPackage()
    {
        return $this->hasOne(SubscriptionPackage::className(), ['id' => 'package_id']);
    }
}
