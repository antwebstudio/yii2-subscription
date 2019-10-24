<?php

namespace ant\subscription\models;

use Yii;
use ant\behaviors\TimestampBehavior;
/**
 * This is the model class for table "em_subscription_package_item".
 *
 * @property string $id
 * @property string $subscription_identity
 * @property string $name
 * @property integer $unit
 * @property integer $valid_period
 * @property integer $content_valid_period
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
            [['subscription_identity', 'name', 'unit', 'valid_period', 'content_valid_period', 'package_id'], 'required'],
            [['unit', 'valid_period', 'content_valid_period', 'status', 'package_id'], 'integer'],
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
            'unit' => 'Subscription Unit',
            'valid_period' => 'Subscription Days',
            'content_valid_period' => 'Content Valid Days',
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
	
	public function toString() {
		return Yii::t('subscription', $this->name, [
			'unit' => $this->unit,
		]);
	}
}
