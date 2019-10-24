<?php

namespace ant\subscription\backend\controllers;

use Yii;
use ant\subscription\models\SubscriptionPackage;
use ant\subscription\models\SubscriptionPackageItem;
use ant\subscription\models\SubscriptionPackageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/**
 * SubscriptionPackageController implements the CRUD actions for SubscriptionPackage model.
 */
class SubscriptionPackageController extends Controller
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
     * Lists all SubscriptionPackage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubscriptionPackageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubscriptionPackage model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SubscriptionPackage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SubscriptionPackage();
        $items = [new SubscriptionPackageItem($this->module->subscriptionPackageItemDefaultValue)];
        $request = Yii::$app->getRequest();
        if ($request->isPost && $request->post('ajax') !== null) {
            return $this->ajaxValidateResult();
        }
        $transaction = Yii::$app->db->beginTransaction();
        if ($post = Yii::$app->request->post()) {
            $items = $this->getPostItems();
            if ($model->load($post) && Model::loadMultiple($items, $post)) {
                if ($model->save()) {
                    $valid = $this->saveSubscriptionPackageItems($items, $model->id);
                    if ($valid) {
                        $model->refresh();
                        $model->updateSubscriptionPackageHistory();
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        $transaction->rollBack();
                        $model->isNewRecord = false;
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'items' => $items,
        ]);
        
    } 

    protected function ajaxValidateResult() {
        $items = $this->getPostItems();
        Model::loadMultiple($items, Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result = ActiveForm::validateMultiple($items);
    }

    protected function getPostItems() {
        $items = [];
        $data = Yii::$app->request->post('SubscriptionPackageItem', []);

        foreach (array_keys($data) as $index) {
            $items[$index] = new SubscriptionPackageItem($this->module->subscriptionPackageItemDefaultValue);
        }
        return $items;
    }

    protected function saveSubscriptionPackageItems($items, $modelId) {
        $valid = true;
        foreach ($items as $item) {
            $item->package_id = $modelId;
            if (!$item->save()) {
                $valid = false;
                break;
            }
        }
        return $valid;
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $items = $model->subscriptionPackageItems;
        if (!$items) {
            $items = [new SubscriptionPackageItem($this->module->subscriptionPackageItemDefaultValue)];
        }
        $request = Yii::$app->getRequest();
        if ($request->isPost && $request->post('ajax') !== null) {
            return $this->ajaxValidateResult();
        }
        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($items, 'id', 'id');
            $items = $this->getPostItems();
            Model::loadMultiple($items, Yii::$app->request->post());
            $transaction = \Yii::$app->db->beginTransaction();
            if ($model->save()) {
                $valid = $this->saveSubscriptionPackageItems($items, $model->id);
                if ($valid) {
                    SubscriptionPackageItem::deleteAll(['id' => $oldIDs]);
                    $model->refresh();
                    $model->updateSubscriptionPackageHistory();
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    $model->isNewRecord = false;
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $items
        ]);
    }

    /**
     * Deletes an existing SubscriptionPackage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SubscriptionPackage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SubscriptionPackage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubscriptionPackage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
