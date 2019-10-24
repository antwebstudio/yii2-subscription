<?php

namespace ant\subscription\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ant\subscription\models\Subscription;

/**
 * SubscriptionSearch represents the model behind the search form about `ant\subscription\models\Subscription`.
 */
class SubscriptionBundleSearch extends SubscriptionBundle
{
    /**
     * @inheritdoc
     */
	public $userId;
    
    public function rules()
    {
        return [
            [['id', 'invoice_id'], 'integer'],
            //[['subscription_identity', 'expire_at', 'created_at', 'updated_at', 'username', 'packageName', 'packagePrice', 'actualPay', 'paymentDate'], 'safe'],
            //[['price'], 'number'],
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
		$query = SubscriptionBundle::find();
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
            'price' => $this->price,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            'invoice_id' => $this->invoice_id,
        ]);
		
		if ($this->userId) {
			$query->andWhere(['owned_by' => $this->userId]);
		}

        return $dataProvider;
    }
}
