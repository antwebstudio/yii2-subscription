<?php

namespace common\modules\subscription\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\subscription\models\SubscriptionPackage;

/**
 * SubscriptionPackageSearch represents the model behind the search form about `common\modules\subscription\models\SubscriptionPackage`.
 */
class SubscriptionPackageSearch extends SubscriptionPackage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_id'], 'integer'],
            [['subscription_identity', 'name', 'created_at', 'updated_at'], 'safe'],
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
        $query = SubscriptionPackage::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'app_id' => $this->app_id,
        ]);

        $query->andFilterWhere(['like', 'subscription_identity', $this->subscription_identity])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
