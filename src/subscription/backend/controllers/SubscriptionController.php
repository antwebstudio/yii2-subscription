<?php

namespace ant\subscription\backend\controllers;

use Yii;
use ant\payment\models\Invoice;
use ant\payment\models\InvoiceItem;
use ant\contact\models\Contact;
use ant\order\models\Order;
use ant\user\models\User;
use ant\subscription\models\Subscription;
use ant\subscription\models\SubscriptionPackage;
use ant\subscription\models\SubscriptionPackageHistory;
use ant\subscription\models\SubscriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubscriptionController implements the CRUD actions for Subscription model.
 */
class SubscriptionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Subscription models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubscriptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render($this->action->id, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionUser($user) {
        $user = isset($user) ? User::findOne($user) : null;

        $searchModel = new SubscriptionSearch();
		$searchModel->userId = $user->id ?? null;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider->query->andWhere(['owned_by' => $user]);
		
        return $this->render($this->action->id, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user ?? null,
        ]);
	}

    /**
     * Displays a single Subscription model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render($this->action->id, [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subscription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($subscriptionDataType = 'default')
    {
        $model = new Subscription($this->module->defaultSubscriptionData[$subscriptionDataType]);
        $invoice = new invoice($this->module->defaultSbuscriptionInvoicedata[$subscriptionDataType]);
        $order = new Order();
        if ($post = Yii::$app->request->post()) {
            // echo "<pre>";
            // print_r($post);
            // echo "</pre>";
            // die;
            $transaction = Yii::$app->db->beginTransaction();

            $buyerId = $post['Subscription']['owned_by'];
            //$customValidDay = isset($post['validDays']) ? $post['validDays'][0] : null;
            if (is_array($buyerId)) {
                foreach ($buyerId as $key => $spiltedId) {
                    $spiltedId = (int)$spiltedId;
                    if (!$model->isNewRecord) {
                        $model = new Subscription($this->module->defaultSubscriptionData[$subscriptionDataType]);
                        $invoice = new invoice($this->module->defaultSbuscriptionInvoicedata[$subscriptionDataType]);
                        $order = new Order();
                    }
                    $order->load($post);
                    $invoice->load($post);

                    $invoice->save();
                    $valid = true;
                    $valid = $this->processedSubscription($spiltedId, $post, $model, $invoice, $order);
                    if (!$valid) {
                    }
                }
                if (!$model->errors) {
                    $transaction->commit();
                    return $this->redirect(['index'
                    ]);
                }
            } 
        } else {
                return $this->render($this->action->id, [
                'model' => $model,
                'invoice' => $invoice,
                'order' => $order,
            ]);
        }
    }

    protected function processedSubscription($buyerId, $post, $model, $invoice, $order){
        $buyer = User::findOne($buyerId);
        $contact = new Contact([
            'status' => 1,
            'firstname' => $buyer->profile->firstname,
            'lastname' => $buyer->profile->lastname,
            'email' => $buyer->email,
        ]);
        $contact->save();
        $order = new Order([
            'status' => 0,
            'confirmation_sent' => 0,
            'invoice_id' => $invoice->id,
            'billed_to' => $contact->id,
        ]);
        if ($order->event_id == null) {
            $defaultSubscriptionPackage = SubscriptionPackage::find()->one();
            if (!$defaultSubscriptionPackage) {
                throw new \Exception("System Error, default subscriptionPackage ID missing", 1);
            } else {
                $subscriptionPackage = SubscriptionPackage::findOne($defaultSubscriptionPackage->id);
                if (!$subscriptionPackage) {
                    throw new \Exception("SubscriptionPackage ID not found.", 1);
                } 
                $order->event_id = $defaultSubscriptionPackage->id ;
            }
        } else {
            $subscriptionPackage = Subscription::findOne($order->event_id);
        }
        $order->save();
        $invoiceItem = new InvoiceItem([
            'invoice_id' => $invoice->id,
            'item_id' => $order->event_id,
            'title' => $subscriptionPackage->name,
            'quantity' => 1,
            'unit_price' => $subscriptionPackage->price,
        ]);
        $invoiceItem->save();

        foreach ($subscriptionPackage->subscriptionPackageItems as $key => $item) {
            $newModel = new Subscription();
            $newModel->attributes = $model->attributes;
            $newModel->invoice_id = $invoice->id;
            $newModel->subscription_identity = $item->subscription_identity;
            $newModel->package_id = SubscriptionPackageHistory::find()->andWhere(['package_id' => $subscriptionPackage->id])
                ->orderBy(['created_at' => SORT_DESC])
                ->one()->id;
            $newModel->load($post);
            //$newModel->expire_at = Subscription::createExpireDate($buyer->id, $item->subscription_days);
            $newModel->expire_at = Subscription::createExpireDate($buyer->id, $newModel->content_valid_days);
            $newModel->owned_by = $buyerId;
            $newModel->detachBehavior('blameable');
            $newModel->save();
            if ($newModel->errors) {
                return false;
            }
        }
        return true;
    }

    /**
     * Updates an existing Subscription model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render($this->action->id, [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Subscription model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Subscription model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subscription the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subscription::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
