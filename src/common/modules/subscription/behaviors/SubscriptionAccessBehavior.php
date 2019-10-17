<?php 
namespace common\modules\subscription\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class SubscriptionAccessBehavior extends Behavior 
{   
    public function execute($params = []) {
        return $this->_checkAccess($params);
    }
        
    protected function _checkAccess($params) {
        if (isset($params['msg'])) {
            $msg = $params['msg'];
        } else {
            $msg = 'subscription';
        }
        $user = $this->user;
        $subscription = Subscription::find()
            ->andWhere(['owned_by' => $user->id])
            ->andWhere(['>', 'expire_at', date('Y-m-d H:i:s')])
            ->one();
        if(!$subscription) {
            throw new ForbiddenHttpException(Yii::t('yii', 'Your ' . $msg . ' is expired.'));
        }
        
        return true;
    }
}
