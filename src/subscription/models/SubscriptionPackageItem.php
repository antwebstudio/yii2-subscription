<?php

namespace ant\subscription\models;

use Yii;
use common\behaviors\TimestampBehavior;
/**
 * This is the model class for table "em_subscription_package_item".
 *
 * @property string $id
 * @property string $subscription_identity
 * @property string $name
 * @property integer $subscription_unit
 * @property integer $subscription_days
 * @property integer $content_valid_days
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $package_id
 *
 * @property SubscriptionPackage $package
 */
class SubscriptionPackageItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subscription_package_item}}';
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
    public function rules()
    {
        return [
            [['subscription_identity', 'name', 'subscription_unit', 'subscription_days', 'content_valid_days', 'package_id'], 'required'],
            [['subscription_unit', 'subscription_days', 'content_valid_days', 'status', 'package_id'], 'integer'],
            [['status'], 'default', 'value' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['subscription_identity'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['package_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubscriptionPackage::className(), 'targetAttribute' => ['package_id' => 'id']],
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
            'subscription_unit' => 'Subscription Unit',
            'subscription_days' => 'Subscription Days',
            'content_valid_days' => 'Content Valid Days',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'package_id' => 'Package ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackage()
    {
        return $this->hasOne(SubscriptionPackage::className(), ['id' => 'package_id']);
    }
}
