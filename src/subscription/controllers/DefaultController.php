<?php
namespace ant\subscription\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use ant\contact\models\Contact;
use ant\address\models\Address;
use ant\subscription\models\Subscription;
use ant\organization\models\Organization;

class DefaultController extends Controller {
    public $layout = '//member-dashboard';
    
	public function actionIndex() {
        return $this->redirect(['/subscription/subscription']);
	}
	
	public function actionUpdateBillingInfo() {
		$organization = Organization::find()->haveCollaborator(Yii::$app->user->id)->one();
		$model = $organization->contact;
		$model->scenario = Contact::SCENARIO_ORGANIZATION_ONLY;

		$address = $model->address;
		$address->scenario = Address::SCENARIO_DEFAULT;
		if (!isset($address->country_id)) $address->country_id = 129;
		
		if ($model->load(Yii::$app->request->post()) && $address->load(Yii::$app->request->post())) 
		{
			if ($model->isUsedIn('ant\payment\models\Invoice', 'billed_to')) {
				$newRecord = $model->saveAsNewRecordIfDirty();
				
				if ($newRecord === true) {
					$result = true;
				} else if (isset($newRecord)) {
					$organization->link('contact', $newRecord);
					$result = true;
				} else {
					$result = false;
				}
			} else {
				$result = $address->save();
				$result = $model->save() && $result;
			}
			
			if ($result) {
				Yii::$app->session->setFlash('success', 'Billing info was updated successfully. ');
				return $this->redirect(\yii\helpers\Url::current());
			}
		}
		
		return $this->render($this->action->id, [
			'model' => $model,
			'address' => $address,
		]);
	}
}
