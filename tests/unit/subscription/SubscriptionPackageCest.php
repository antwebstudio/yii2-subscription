<?php 
namespace subscription;

use UnitTester;
use common\modules\subscription\models\Subscription;
use common\modules\subscription\models\SubscriptionPackage;

class SubscriptionPackageCest
{
    public function _before(UnitTester $I)
    {
    }

    // tests
    public function testSubscribe(UnitTester $I)
    {
        $user = $I->grabFixture('user')->getModel(0);
        $package = $I->grabFixture('subscriptionPackage')->getModel(0);
        $bundle = $package->subscribe($user);

        $subscriptionQuery = Subscription::find()->andWhere(['owned_by' => $user->id]);
        $subscriptions = $subscriptionQuery->all();

        $I->assertTrue(count($subscriptions) > 0);
        $I->assertEquals(count($package->packageItems), count($subscriptions));
        // Assert bundle record
        $I->assertEquals($package->id, $bundle->package_id);
        // Assert subscription record
        $I->assertEquals($package->id, $subscriptions[0]->package_id);
        $I->assertEquals($user->id, $subscriptions[0]->owned_by);
        $I->assertEquals($bundle->id, $subscriptions[0]->bundle_id);
    }
	
	public function _fixtures()
    {
        return [
            'contact' => [
                'class' => \tests\fixtures\ContactFixture::className(),
            ],
            'user' => [
                'class' => \tests\fixtures\UserFixture::className(),
            ],
            'userProfile' => [
                'class' => \tests\fixtures\UserProfileFixture::className(),
            ],
			'subscription' => [
				'class' => \tests\fixtures\SubscriptionFixture::class,
            ],
            'subscriptionPackage' => [
				'class' => \tests\fixtures\SubscriptionPackageFixture::class,
			],
            'subscriptionPackageItem' => [
				'class' => \tests\fixtures\SubscriptionPackageItemFixture::class,
			],
        ];
    }
}
