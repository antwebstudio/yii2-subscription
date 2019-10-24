<?php
//namespace tests\codeception\common\subscription;

//use Yii;
use yii\helpers\Html;
//use tests\codeception\common\UnitTester;
use ant\user\models\User;
use ant\subscription\models\SubscriptionPackage;
use ant\subscription\models\SubscriptionPackageItem;

class SubscriptionCest
{
    protected $identity = 'test';

    public function _before(UnitTester $I)
    {
        $package = new SubscriptionPackage(['subscription_identity' => $this->identity]);
        $package->attributes = [
            'name' => 'test subscription package',
            'price' => 200,
        ];
        if (!$package->save()) throw new \Exception(Html::errorSummary($package));

        $packageItem = new SubscriptionPackageItem(['subscription_identity' => $this->identity]);
        $packageItem->attributes = [
            'package_id' => $package->id,
            'name' => 'test item',
			'valid_period' => 0,
			'content_valid_period' => 0,
            'unit' => 0,
        ];
        if (!$packageItem->save()) throw new \Exception(Html::errorSummary($packageItem));

        Yii::configure(Yii::$app, [
            'components' => [
                'subscription' => [
                    'class' => \ant\subscription\components\SubscriptionComponent::className(),
                    'creditSubscriptionIdentity' => $this->identity,
                ],
            ],
        ]);
    }

    public function _after(UnitTester $I)
    {
    }

    public function testAddCredit(UnitTester $I) {
        $user = $this->createUser();
        $I->assertEquals(0, Yii::$app->subscription->getCreditBalance($user->id));

        Yii::$app->subscription->addCredit($user->id, 200);
        $I->assertEquals(200, Yii::$app->subscription->getCreditBalance($user->id));
    }

    public function testAddCreditSum(UnitTester $I) {
        $user = $this->createUser();
        $I->assertEquals(0, Yii::$app->subscription->getCreditBalance($user->id));

        Yii::$app->subscription->addCredit($user->id, 100);
        Yii::$app->subscription->addCredit($user->id, 100);
        $I->assertEquals(200, Yii::$app->subscription->getCreditBalance($user->id));
    }

    // tests
    public function testUseCredit(UnitTester $I)
    {
        $user = $this->createUser();
        $I->assertEquals(0, Yii::$app->subscription->getCreditBalance($user->id));
        
        Yii::$app->subscription->addCredit($user->id, 200);
        Yii::$app->subscription->useCredit($user->id, 100); // Use less than a record
        $I->assertEquals(100, Yii::$app->subscription->getCreditBalance($user->id));
    }

    public function testUseCreditMoreThanOneRecord(UnitTester $I)
    {
        $user = $this->createUser();
        $I->assertEquals(0, Yii::$app->subscription->getCreditBalance($user->id));
        
        Yii::$app->subscription->addCredit($user->id, 100);
        Yii::$app->subscription->addCredit($user->id, 200);

        Yii::$app->subscription->useCredit($user->id, 250); // Use more than a record
        $I->assertEquals(50, Yii::$app->subscription->getCreditBalance($user->id));
    }

    protected function createUser() {
        $user = new User(['registered_ip' => '::1']);
        $user->attributes = [
            'username' => 'test_'.uniqid(),
            'email' => uniqid().'@email.com',
        ];
        $user->generateAuthKey();
        $user->setPassword('test');
        if (!$user->save()) throw new \Exception(Html::errorSummary($user));

        return $user;
    }
}
