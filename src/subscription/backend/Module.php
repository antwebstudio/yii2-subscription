<?php

namespace ant\subscription\backend;

use ant\subscription\models\Subscription;
use ant\payment\models\Invoice;
use ant\subscription\models\SubscriptionPackage;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/**
 * subscribe module definition class
 */
class Module extends \yii\base\Module
{
    public $subscriptionPackageIdentiy = [
        'Member' => 'Member',
    ];

    public $testing = null;

    // public $subscriptionFormData = null;

    protected $_subscriptionFormData = null;

    public function getSubscriptionFormData() 
    {
        return $this->_subscriptionFormData;
    }

    public function setSubscriptionFormData($value) 
    {
        if (is_callable($value)) $value = call_user_func_array($value, [$this]);
        $this->_subscriptionFormData = $value;
    }

    public function getDefaultSubscriptionFormData() {
        return ArrayHelper::map(SubscriptionPackage::find()->andWhere(['subscription_identity' => ['member']])->all(), 'id', 'name');
    }

    public $subscriptionPackageItemDefaultValue = ['status' => 1 , 'content_valid_days' => 1, 'subscription_unit' => 1];

    public function getSubscriptionPackageFormColumns() {
        return         
        [
            [
                'name'  => 'name',
                'title' => 'Item Name',
                'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_TEXT_INPUT,
            ],
            [
                'name'  => 'subscription_identity',
                'title' => 'Subscription Identiy',
                'type'  => \kartik\select2\Select2::className(),
                'options' => [
                    'data' => [
                        'Member' => 'Member',
                    ],
                    'maintainOrder' => true,
                    'options' => [
                        'placeholder' => '',
                        'multiple' => false
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]
            ],
            // [
            //     'name'  => 'subscription_unit',
            //     'title' => 'Subscription Unit',
            // ],
            [
                'name'  => 'subscription_days',
                'title' => 'Subscription Days',
            ],
            // [
            //     'name'  => 'content_valid_days',
            //     'title' => 'Cotent Valid Days',
            // ],
            // [
            //     'name'  => 'app_id',
            //     'title' => 'App ID',
            // ],
        ];
    }

    public $subscriptionPackageFormMax = 1; 

    public $defaultSubscriptionData = [
        'default' => [
            'purchased_unit' => 0,
            'used_unit' => 0,
            'status' => Subscription::DEFAULT_VALUE_STATUS,
            'content_valid_days' => 0,
        ]
    ];
    public $defaultSbuscriptionInvoicedata = [
        'default' => [
            'total_amount' => 1,
            'discount_amount' => 0,
            'service_charges_amount' => 0,
            'tax_amount' => 0,
            'paid_amount' => 1,
            'issue_to' => 1, // assume rbac working
            'remark' => null,
            'status' => Invoice::STATUS_ACTIVE,
        ]
    ];

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
