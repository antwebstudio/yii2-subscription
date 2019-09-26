<?php

namespace common\modules\subscription\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\user\models\User;
use common\modules\user\models\UserSearch;
use common\behaviors\TimestampBehavior;

/**
 * UserSearch represents the model behind the search form about `common\modules\user\models\User`.
 */
class SubscriptionUserSearch extends UserSearch
{
    public $noSubscribe = null;
    public $expireDate;
    public $price;
    public $content_valid_days;

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['expireDate', 'content_valid_days', 'price'], 'safe'];
        return $rules;
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
        $dataProvider = parent::search($params);
        $query = $dataProvider->query;
        $query->join('LEFT JOIN','{{%subscription}}','{{%subscription}}'.'.owned_by = {{%user}}'.'.id');
        if ($this->noSubscribe) {
            $query->andWhere([
                'or', 
                ['<', 'DATE(expire_date)' , date('Y-m-d')],
                ['expire_date' => null],
            ]);
            $dataProvider->setSort([
                'attributes' => [
                    'expireDate' => [
                        'asc' => ['expire_date' => SORT_ASC],
                        'desc' => ['expire_date' => SORT_DESC],
                        'label' => 'Last Subscription',
                    ],                    
                    'price' => [
                        'asc' => ['expire_date' => SORT_ASC],
                        'desc' => ['expire_date' => SORT_DESC],
                    ],
                    'content_valid_days' => [
                        'asc' => ['content_valid_days' => SORT_ASC],
                        'desc' => ['content_valid_days' => SORT_DESC],
                    ],
                    'username' => [
                        'asc' => ['username' => SORT_ASC],
                        'desc' => ['username' => SORT_DESC],
                    ],
                ]
            ]);
            // echo "<pre>";
            // print_r($query->createCommand()->rawSql);
            // echo "</pre>";
            // die;
            // $query
            // ->andFilterWhere(['<','expire_date' , date('Y-m-d')])
            // ->andFilterWhere(['owned_by' => '{{%user}}'.'.id']);
            $query->andFilterWhere(['like', 'expire_date', $this->expireDate]);
        }
        return $dataProvider;
    }
}