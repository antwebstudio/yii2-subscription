<?php

namespace ant\subscription\controllers;


class BundleController extends Controller {
	public function actionUser($user) {
        $searchModel = new SubscriptionBundleSearch();
		$searchModel->userId = $user;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider->query->andWhere(['owned_by' => $user]);
		
        return $this->render($this->action->id, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
}