<?php

namespace common\modules\subscription\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\subscription\models\Subscription;

/**
 * SubscriptionSearch represents the model behind the search form about `common\modules\subscription\models\Subscription`.
 */
class SubscriptionSearch extends Subscription
{
    /**
     * @inheritdoc
     */

    public $username;
    public $packageName;
    public $packagePrice;
    public $actualPay;
    public $paymentDate;

    public $query = null;
    
    public function rules()
    {
        return [
            [['id', 'app_id', 'purchased_unit', 'used_unit', 'content_valid_days', 'status', 'owned_by', 'invoice_id'], 'integer'],
            [['subscription_identity', 'expire_date', 'created_at', 'updated_at', 'username', 'packageName', 'packagePrice', 'actualPay', 'paymentDate'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        if (!$this->query) {
            $query = Subscription::find();
        } else {
            $query = $this->query;
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'app_id' => $this->app_id,
            'price' => $this->price,
            'purchased_unit' => $this->purchased_unit,
            'used_unit' => $this->used_unit,
            'content_valid_days' => $this->content_valid_days,
            'status' => $this->status,
            'owned_by' => $this->owned_by,
            'expire_date' => $this->expire_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'invoice_id' => $this->invoice_id,
        ]);

        if ($this->username) {
            $query->andFilterWhere(['like', 'user.username' , $this->username]);
        }
        if ($this->packagePrice) {
            $query->andFilterWhere(['like', 'package.price' , $this->packagePrice]);
        }
        if ($this->packageName) {
            $query->andFilterWhere(['like', 'package.name' , $this->packageName]);
        }
        if ($this->actualPay) {
            $query->andFilterWhere(['like', 'invoice.paid_amount' , $this->actualPay]);
        }
        if ($this->paymentDate) {
            $query->andFilterWhere(['like', 'invoice.paid_amount' , $this->paymentDate]);
        }

        $query->andFilterWhere(['like', 'subscription_identity', $this->subscription_identity]);

        return $dataProvider;
    }
}
