<?php

namespace ant\subscription\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\subscription\models\SubscriptionPackageHistory;

/**
 * SubscriptionPackageSearchHistory represents the model behind the search form about `common\modules\subscription\models\SubscriptionPackageHistory`.
 */
class SubscriptionPackageSearchHistory extends SubscriptionPackageHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'package_id'], 'integer'],
            [['name', 'created_at'], 'safe'],
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
        $query = SubscriptionPackageHistory::find();

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
            'package_id' => $this->package_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
