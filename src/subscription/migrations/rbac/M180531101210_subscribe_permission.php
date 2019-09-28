<?php

namespace ant\subscription\migrations\rbac;

use yii\db\Schema;
use common\rbac\Migration;
use common\rbac\Role;
use ant\subscription\backend\controllers\SubscriptionController;
use ant\subscription\backend\controllers\SubscriptionPackageController;
use ant\subscription\backend\controllers\SubscriptionPackageHistoryController;
use ant\subscription\backend\controllers\SubscribedMemberController;
use ant\subscription\backend\controllers\SubscriptionHistoryController;

class M180531101210_subscribe_permission extends Migration
{
	protected $permissions;

	public function init()
    {
		$this->permissions = [
			SubscriptionController::className() => [
				'index' => ['index subscribed', [Role::ROLE_ADMIN]],
				'user' => ['Subscription subscribed by a user', [Role::ROLE_ADMIN]],
				'create' => ['create offline subscription', [Role::ROLE_ADMIN]],
				'update' => ['update offline subscription', [Role::ROLE_ADMIN]],
				'delete' => ['delete offline subscription', [Role::ROLE_ADMIN]],
				'view' => ['view offline subscription', [Role::ROLE_ADMIN]],
			],
			SubscriptionPackageHistoryController::className() => [
				'index' => ['subscription history index', [Role::ROLE_ADMIN]],
				//'create' => ['create offline subscription', [Role::ROLE_ADMIN]],
				//'update' => ['update offline subscription', [Role::ROLE_ADMIN]],
				//'delete' => ['delete offline subscription', [Role::ROLE_ADMIN]],
				'view' => ['view offline subscription', [Role::ROLE_ADMIN]],
			],
			SubscribedMemberController::className() => [
				'index' => ['index subscribed', [Role::ROLE_ADMIN]],
				'view' => ['view offline subscription', [Role::ROLE_ADMIN]],
			],
			SubscriptionHistoryController::className() => [
				'index' => ['index subscribed', [Role::ROLE_ADMIN]],
			],
			SubscriptionPackageController::className() => [
				'index' => ['index subscribed', [Role::ROLE_ADMIN]],
				'create' => ['create offline subscription', [Role::ROLE_ADMIN]],
				'update' => ['update offline subscription', [Role::ROLE_ADMIN]],
				'delete' => ['delete offline subscription', [Role::ROLE_ADMIN]],
				'view' => ['view offline subscription', [Role::ROLE_ADMIN]],
			],
			\ant\subscription\controllers\DefaultController::className() => [
				'index' => ['Subscription dashboard', [Role::ROLE_USER]],
			]
		];

		parent::init();
    }

	public function up()
    {
		$this->addAllPermissions($this->permissions);
    }

    public function down()
    {
		$this->removeAllPermissions($this->permissions);
    }
}
