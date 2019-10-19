<?php

namespace ant\subscription\backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * SubscriptionController implements the CRUD actions for Subscription model.
 */
class UserController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /*'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],*/
        ];
    }
	
    public function actionIndex()
    {
    }

    /**
     * Displays a single Subscription model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		//$subscriptions
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
}
