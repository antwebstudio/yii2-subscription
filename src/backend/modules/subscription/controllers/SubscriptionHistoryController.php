<?php

namespace backend\modules\subscription\controllers;

use Yii;
use common\modules\payment\models\Invoice;
use common\modules\payment\models\InvoiceItem;
use common\modules\contact\models\Contact;
use common\modules\order\models\Order;
use common\modules\user\models\User;
use common\modules\subscription\models\Subscription;
use common\modules\subscription\models\SubscriptionPackage;
use common\modules\subscription\models\SubscriptionPackageHistory;
use common\modules\subscription\models\SubscriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubscriptionController implements the CRUD actions for Subscription model.
 */
class SubscriptionHistoryController extends Controller
{
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
    public function actionIndex($id = null)
    {

        $query = Subscription::find();
        if (isset($id)) {
            $query->andFilterWhere(['s.id' => $id]);
        } else {
        }
        $query->alias('s')
        //->select('MAX(s.id)') // take last invoice record for paid_amount
        ->select('s.invoice_id, MAX(s.id) as id , s.owned_by ,s.package_id') // take last invoice record for paid_amount
        ->joinWith('user user')
        ->joinWith('subscriptionPackage package')
        ->joinWith('invoice invoice')
        ->groupBy(['s.invoice_id']);

        //$query = Subscription::find()->andWhere(['id' => $subquery]);

        $searchModel = new SubscriptionSearch(
            ['query' => $query]
        );
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subscription model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subscription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate($subscriptionDataType = 'default')
    // {
    //     $model = new Subscription($this->module->defaultSubscriptionData[$subscriptionDataType]);
    //     $invoice = new invoice($this->module->defaultSbuscriptionInvoicedata[$subscriptionDataType]);
    //     $order = new Order();
    //     if ($post = Yii::$app->request->post()) {
    //         // echo "<pre>";
    //         // print_r($post);
    //         // echo "</pre>";
    //         // die;
    //         $transaction = Yii::$app->db->beginTransaction();

    //         $buyerId = $post['Subscription']['owned_by'];
    //         //$customValidDay = isset($post['validDays']) ? $post['validDays'][0] : null;
    //         if (is_array($buyerId)) {
    //             foreach ($buyerId as $key => $spiltedId) {
    //                 $spiltedId = (int)$spiltedId;
    //                 if (!$model->isNewRecord) {
    //                     $model = new Subscription($this->module->defaultSubscriptionData[$subscriptionDataType]);
    //                     $invoice = new invoice($this->module->defaultSbuscriptionInvoicedata[$subscriptionDataType]);
    //                     $order = new Order();
    //                 }
    //                 $order->load($post);
    //                 $invoice->load($post);

    //                 $invoice->save();
    //                 $valid = true;
    //                 $valid = $this->processedSubscription($spiltedId, $post, $model, $invoice, $order);
    //                 if (!$valid) {
    //                     break;
    //                 }
    //             }
    //             if ($valid) {
    //                 $transaction->commit();
    //                 return $this->redirect(['index'
    //                 ]);
    //             } else {
    //                 throw new \Exception($model->errors, 1);
    //             }
    //         } 
    //     } else {
    //             return $this->render('create', [
    //             'model' => $model,
    //             'invoice' => $invoice,
    //             'order' => $order,
    //         ]);
    //     }
    // }

    // protected function processedSubscription($buyerId, $post, $model, $invoice, $order){
    //     $buyer = User::findOne($buyerId);
    //     $contact = new Contact([
    //         'status' => 1,
    //         'firstname' => $buyer->profile->firstname,
    //         'lastname' => $buyer->profile->lastname,
    //         'email' => $buyer->email,
    //     ]);
    //     $contact->save();
    //     $order = new Order([
    //         'status' => 0,
    //         'confirmation_sent' => 0,
    //         'invoice_id' => $invoice->id,
    //         'billed_to' => $contact->id,
    //     ]);
    //     if ($order->event_id == null) {
    //         $defaultSubscriptionPackage = SubscriptionPackage::find()->one();
    //         if (!$defaultSubscriptionPackage) {
    //             throw new \Exception("System Error, default subscriptionPackage ID missing", 1);
    //         } else {
    //             $subscriptionPackage = SubscriptionPackage::findOne($defaultSubscriptionPackage->id);
    //             if (!$subscriptionPackage) {
    //                 throw new \Exception("SubscriptionPackage ID not found.", 1);
    //             } 
    //             $order->event_id = $defaultSubscriptionPackage->id ;
    //         }
    //     } else {
    //         $subscriptionPackage = Subscription::findOne($order->event_id);
    //     }
    //     $order->save();
    //     $invoiceItem = new InvoiceItem([
    //         'invoice_id' => $invoice->id,
    //         'item_id' => $order->event_id,
    //         'title' => $subscriptionPackage->name,
    //         'quantity' => 1,
    //         'unit_price' => $subscriptionPackage->price,
    //     ]);
    //     $invoiceItem->save();

    //     foreach ($subscriptionPackage->subscriptionPackageItems as $key => $item) {
    //         $newModel = new Subscription();
    //         $newModel->attributes = $model->attributes;
    //         $newModel->invoice_id = $invoice->id;
    //         $newModel->subscription_identity = $item->subscription_identity;
    //         $newModel->package_id = SubscriptionPackageHistory::find()->andWhere(['package_id' => $subscriptionPackage->id])
    //             ->orderBy(['created_at' => SORT_DESC])
    //             ->one()->id;
    //         $newModel->load($post);
    //         //$newModel->expire_date = Subscription::createExpireDate($buyer->id, $item->subscription_days);
    //         $newModel->expire_date = Subscription::createExpireDate($buyer->id, $newModel->content_valid_days);
    //         $newModel->owned_by = $buyerId;
    //         $newModel->detachBehavior('blameable');
    //         $newModel->save();
    //         if ($newModel->errors) {
    //             return false;
    //         }
    //     }
    // }

    // /**
    //  * Updates an existing Subscription model.
    //  * If update is successful, the browser will be redirected to the 'view' page.
    //  * @param integer $id
    //  * @return mixed
    //  */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     } else {
    //         return $this->render('update', [
    //             'model' => $model,
    //         ]);
    //     }
    // }

    // /**
    //  * Deletes an existing Subscription model.
    //  * If deletion is successful, the browser will be redirected to the 'index' page.
    //  * @param integer $id
    //  * @return mixed
    //  */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

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
