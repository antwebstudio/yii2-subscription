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
class SubscriptionUserSearch extends \ant\subscription\models\SubscriptionUserSearch
{
}