<?php
namespace frontend\modules\subscription\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use common\modules\subscription\models\Subscription;

class DefaultController extends Controller {
    public $layout = '//member-dashboard';
    
	public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Subscription::find()->ownedBy(Yii::$app->user->id)->limit(10),
            'sort' => false,
        ]);
        $dataProvider->pagination = false;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
	}
}
